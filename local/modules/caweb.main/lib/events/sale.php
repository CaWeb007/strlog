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
        $order = \Caweb\Main\Events\Helper::updateProperty($order);
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
        if ($_REQUEST['action'] === 'delete') return;
        /**@var $order \Bitrix\Sale\Order*/
        $order = $event->getParameter('ENTITY');
        Helper::checkNeedChangeProductPrice($order);
    }
    public static function linoMinBalanceController(Main\Event $event){
        $offerIblockXmlId = '1c_catalog-123456#';
        $propertyWidthId = 594;
        $enumSliceId = 6389;
        $minBalance = 15;
        $isAddAction = $_REQUEST['add_item'] === 'Y';
        /** @var \Bitrix\Sale\Basket $basket */
        $basket = $event->getParameter("ENTITY");
        $basketItemsCollection = $basket->getBasketItems();
        /**@var $item \Bitrix\Sale\BasketItem*/
        foreach ($basketItemsCollection as $item){
            if ($item->getField('CATALOG_XML_ID') !== $offerIblockXmlId) continue;
            $itemId = (int)$item->getProductId();
            $dbElement = \CIBlockElement::GetByID($itemId)->GetNextElement();
            $isLino = stripos($dbElement->GetFields()['NAME'], Loc::getMessage('REG_LINO'));
            if ($isLino === false) continue;
            $propertyWidthValue = (int)$dbElement->GetProperty($propertyWidthId)['VALUE_ENUM_ID'];
            $isSlice = $propertyWidthValue === $enumSliceId;
            if (!$isSlice) continue;
            $balance = (float)\Bitrix\Catalog\ProductTable::getRowById($itemId)['QUANTITY'];
            $maxBuyQuantity = $balance - $minBalance;
            if (($item->getQuantity() > $maxBuyQuantity) && ($item->getQuantity() != $balance)){
                $errorText = Loc::getMessage('CES_MAX_QUANTITY_ERROR', array('#MAX_QUANTITY_BUY#' => $maxBuyQuantity, '#BALANCE#' => $balance));
                if (!$isAddAction){
                    $item->setFieldsNoDemand(array('QUANTITY' => $maxBuyQuantity));
                    $item->save();
                }
                return new Main\EventResult(
                    Main\EventResult::ERROR,
                    \Bitrix\Sale\ResultError::create(new Main\Error($errorText, "ERROR_MAX_QUANTITY_LINO"))
                );
            }
        }
    }
}