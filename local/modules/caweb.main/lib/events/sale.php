<?
namespace Caweb\Main\Events;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Entity;
use Bitrix\Main;
use Bitrix\Sale\Internals\ShipmentTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValue;
use Caweb\Main\Log\Write;
use Caweb\Main\User\Exchange;
Loc::loadLanguageFile(__FILE__);
class Sale{
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
    public function OnSaleOrderBeforeSaved(Main\Event $event){
        /** @var \Bitrix\Sale\Order $order */
        $parameters = $event->getParameters();
        $order = $parameters['ENTITY'];
        $properties = $order->getPropertyCollection();
        $personType = (int)$order->getPersonTypeId();
        if ($personType === 1){
            $fio = $properties->getItemByOrderPropertyId(1);
            $fio2 = $properties->getItemByOrderPropertyId(31);
            $name = $properties->getItemByOrderPropertyId(32);
            $family = $properties->getItemByOrderPropertyId(33);
            $lastName = $properties->getItemByOrderPropertyId(34);
        }else{
            $fio = $properties->getItemByOrderPropertyId(12);
            $fio2 = $properties->getItemByOrderPropertyId(35);
            $name = $properties->getItemByOrderPropertyId(36);
            $family = $properties->getItemByOrderPropertyId(37);
            $lastName = $properties->getItemByOrderPropertyId(38);
        }
        if (($fio2 instanceof PropertyValue) && $fio2->getValue()){
            $fio->setValue($fio2->getValue());
        }elseif(($name instanceof PropertyValue)
            && ($family instanceof PropertyValue) && ($lastName instanceof PropertyValue)){
            $fio->setValue(implode(' ', array($family->getValue(), $name->getValue(), $lastName->getValue())));
        }else{
            $fio->setValue('unknown');
        }
    }
    public function CheckDoExchange($arFields){
        if (!Exchange::$doExchange)
            return false;
    }
}