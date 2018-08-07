FROM centos:6
MAINTAINER rmkn
RUN localedef -f UTF-8 -i ja_JP ja_JP.utf8 && sed -i -e "s/en_US.UTF-8/ja_JP.UTF-8/" /etc/sysconfig/i18n
RUN cp -p /usr/share/zoneinfo/Japan /etc/localtime && echo 'ZONE="Asia/Tokyo"' > /etc/sysconfig/clock
RUN yum -y update
RUN yum -y install postfix rsyslog php

ENV LANG ja_JP.UTF-8

RUN useradd mbh

COPY virtual_mailbox_maps /etc/postfix/
COPY main.cf.add master.cf.add mbh.php /tmp/
RUN cat /tmp/main.cf.add >> /etc/postfix/main.cf
RUN cat /tmp/master.cf.add >> /etc/postfix/master.cf
RUN sed -i -e "s/inet_interfaces = localhost/inet_interfaces = all/" /etc/postfix/main.cf

CMD ["sh", "-c", "service rsyslog start ; service postfix start ; tail -F /var/log/maillog"]
