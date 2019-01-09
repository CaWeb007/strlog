<?
namespace Caweb\Main\Migration;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;

class Helper{
    public static function init($class){
        $object = Base::getInstance($class);
        if (!Application::getConnection()->isTableExists($object->getDBTableName()))
            $object->createDbTable();
    }
}