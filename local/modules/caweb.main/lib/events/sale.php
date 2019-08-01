<?
namespace Caweb\Main\Events;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Entity;
use Bitrix\Main;
use Caweb\Main\Log\Write;

Loc::loadLanguageFile(__FILE__);
class Sale{
    public function OnSaleOrderBeforeSaved(Main\Event $event){
        $get = Context::getCurrent()->getRequest()->get('mode');
        $order = $event->getParameter('ENTITY');
        Debug::dumpToFile(array('get' => $get, 'request' => $_REQUEST, 'order' => $order), 'OnSaleOrderBeforeSaved', 'caweb.log', true);
    }
    public function OnBeforeShipmentDeleted(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $res = new Entity\EventResult;
            $res->addError(new Entity\EntityError('shipment_delete_cancel'));
            $entity = $event->getEntity();
            $entity->getFullName();
            Write::file('orders/ShipmentHandler', $entity, true);
            return $res;
        }
    }
    public function OnBeforeShipmentDelete(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $res = new Entity\EventResult;
            $res->addError(new Entity\EntityError('shipment_delete_cancel'));
            $entity = $event->getEntity();
            $entity->getFullName();
            Write::file('orders/ShipmentHandlerD', $entity, true);
            return $res;
        }
    }
    public function OnBeforePaymentDelete(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $res = new Entity\EventResult;
            $res->addError(new Entity\EntityError('shipment_delete_cancel'));
            $entity = $event->getEntity();
            $entity->getFullName();
            Write::file('orders/payHandlerD', $entity, true);
            return $res;
        }
    }
}