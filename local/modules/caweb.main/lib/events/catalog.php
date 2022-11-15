<?
namespace Caweb\Main\Events;

use Bitrix\Catalog\Model\Event;
use Bitrix\Catalog\Model\EventResult;
use Bitrix\Main\Localization\Loc;
use Caweb\Main\Catalog\Helper;

Loc::loadLanguageFile(__FILE__);
class Catalog{

    public function OnBeforeProductUpdate(Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $params = $event->getParameters();
            if (((int)$params['id'] === 20211) && !is_null($quantity = $params['fields']['QUANTITY'])){
                $res = new EventResult();
                $res->modifyFields(array('QUANTITY' => $quantity * 20));
                $event->addResult($res);
            }
        }
    }
    public static function storeActiveController(&$field1, &$field2 = null){
        if (($field2 !== null) && (!in_array((int)$field1, Helper::ACTIVE_STORE_IDS))){
            $field2['ACTIVE'] = 'N';
        }elseif (is_array($field1)){
            $field1['ACTIVE'] = 'N';
        }
    }
}