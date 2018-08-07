#!/usr/bin/php
<?php
// vim: set et ts=4 sw=4 sts=4:

$rcptTo   = isset($argv[1]) ? $argv[1] : null;
$mailFrom = isset($argv[2]) ? $argv[2] : null;

if ($rcptTo !== null && $mailFrom !== null) {
    $mg = new MailBH($mailFrom, $rcptTo);
    $mg->execute();
}

class MailBH
{
    const DEBUG = true;

    private $mailFrom;
    private $rcptTo;
    private $mailData;

    public function __construct($mailFrom, $rcptTo)
    {
        $this->mailFrom = $mailFrom;
        $this->rcptTo   = $rcptTo;
        $this->getMaildata();
    }

    private function getMaildata()
    {
        $this->mailData = null;

        $r = array(STDIN);
        $w = null;
        $e = null;
        $rc = stream_select($r, $w, $e, 0);
        if ($rc !== false && $rc > 0) {
            $this->mailData = file_get_contents('php://stdin');
        }
    }

    public function execute()
    {
        $fn = tempnam(sys_get_temp_dir(), 'mbh_');
        file_put_contents($fn, $this->mailData);
    }
}

