<?
namespace Caweb\Main\Events;

use Bitrix\Catalog\Model\Event;
use Bitrix\Catalog\Model\EventResult;
use Bitrix\Main\Localization\Loc;

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
}