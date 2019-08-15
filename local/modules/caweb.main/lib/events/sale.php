<?
namespace Caweb\Main\Events;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Entity;
use Bitrix\Main;
use Bitrix\Sale\Internals\ShipmentTable;
use Bitrix\Sale\Order;
use Caweb\Main\Log\Write;

Loc::loadLanguageFile(__FILE__);
class Sale{
    public function OnSaleOrderBeforeSaved(Main\Event $event){
        $order = $event->getParameter('ENTITY');
        /**@var $order Order*/
        if ($_REQUEST['mode'] == 'import'){
            //Write::file('orders/OnSaleOrderBeforeSaved2', $order, true);
        }
    }

    public function OnBeforeShipmentDelete(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $res = new Entity\EventResult;
            $res->addError(new Entity\EntityError('shipment_delete_cancel'));
            return $res;
        }
    }
    public function OnBeforePaymentUpdate(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){

            //Write::file('salePaymentUpdate', $event);
        }
    }
    public function OnBeforePaymentDelete(Entity\Event $event){
        if ($_REQUEST['mode'] == 'import'){
            $res = new Entity\EventResult;
            $res->addError(new Entity\EntityError('shipment_delete_cancel'));

            return $res;
        }
    }
}