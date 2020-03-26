<?
namespace Caweb\Main\Log;

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\IO\File;
use Bitrix\Main\Type\DateTime;

class Write{
    const DIR = 'logs/caweb/';
    const TYPE = '.log';
    protected $logArray = array();
    public static function file($name, $var, $rewrite = false){
        $path = self::DIR.$name.self::TYPE;
        $timeInt = new DateTime();
        $time = $timeInt->format('m-d H:i');
        $timeInt->add('-10 hour');
        $timeInt = $timeInt->getTimestamp();
        $file = new File($path);
        if ($file->isExists() && ($timeInt > $file->getCreationTime()))
            $file->putContents('');
        if ($file->isExists() && $rewrite)
            $file->putContents('');
        Debug::dumpToFile($var,$time, $path);
    }
    public function setLogArray($key, $value){
        $this->logArray[$key][] = $value;
    }
    public function dumpLog($name, $flag){
        if (!$flag) return;
        static::file($name, $this->logArray, true);
    }
}