<?
namespace Caweb\Main\Agent;

use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;

class CleanScheta {
    public static function clean(){
        $dRoot = Application::getDocumentRoot();
        $dir = new Directory($dRoot.'/upload/tmp/scheta/');
        $files = $dir->getChildren();
        foreach ($files as $file){
            if ($file instanceof File)
                $file->delete();
        }
        return '\Caweb\Main\Agent\CleanScheta::clean();';
    }
}