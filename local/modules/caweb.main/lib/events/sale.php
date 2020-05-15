<?
namespace Caweb\Main\Events;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main;
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
        $event->addResult(new Main\EventResult(
            Main\EventResult::SUCCESS,
            \Caweb\Main\Sale\Helper::updateOrderProperties($order)
        ));
    }
    public function CheckDoExchange($arFields){
        if (!Exchange::$doExchange)
            return false;
    }
    public function priceFromPaySystemOrderEntity(Main\Event $event){
        /**@var $order \Bitrix\Sale\Order*/
        $order = $event->getParameter('ENTITY');
        Helper::checkNeedChangeProductPrice($order);
    }
}