<?php
namespace HMorm;

class Log
{
    private static $path = '';
    public function __construct($setPath = "")
    {
        self::$path = self::$path ? self::$path : ($setPath ? $setPath : "./runtime/log/");
    }
    public static function setLogPath($path)
    {
        self::$path = $path;
    }
    public function write($message, $fileSalt)
    {
        $date = new \DateTime();
        $log = self::$path ."/". $date->format('Y-m-d') . "-" . md5($date->format('Y-m-d') . $fileSalt) . ".txt";
        if (is_dir(self::$path)) {
            if (!file_exists($log)) {
                $fh = fopen($log, 'a+') or die("Fatal Error !");
                $logcontent = "Time : " . $date->format('H:i:s') . "\r\n" . $message . "\r\n";
                fwrite($fh, $logcontent);
                fclose($fh);
            } else {
                $this->edit($log, $date, $message);
            }
        } else {
            if (mkdir(self::$path, 0777, true) === true) {
                $this->write($message, $fileSalt);
            }
        }
    }
    private function edit($log, $date, $message)
    {
        $logcontent = "Time : " . $date->format('H:i:s') . "\r\n" . $message . "\r\n\r\n";
        $logcontent = $logcontent . file_get_contents($log);
        file_put_contents($log, $logcontent);
    }
}
