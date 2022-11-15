<?
namespace Caweb\Main\Events;

use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\StoreTable;
use Bitrix\Catalog\SubscribeTable;
use Bitrix\Main\DB\Exception;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Internals\DiscountTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\PropertyValue;
use Bitrix\Sale\Shipment;
use Caweb\Main\Catalog\Helper as CatalogHelper;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);
class Helper{
    public static $REFRESH_PRICES = false;
    public static $PRICE_CHANGED = false;
    const KP_PRICE_ID = 11;
    const PAYSYSTEMS_NEED_CHANGE_PRICE = array(13,14,15,16);//todo КОСТЫЛЬ КОНЕЧНО, но не забыть установить
    public static function checkLegal($fields){
        global $APPLICATION;
        $post = Application::getInstance()->getContext()->getRequest()->getPost('not_use_kpp');
        $notUseKpp = ($post === 'YES');
        if (empty($fields['UF_INN'])){
            $APPLICATION->ThrowException(Loc::getMessage('HELPER_CHECK_LEGAL_ENTER_INN'));
            return false;
        }
        if (!$notUseKpp && empty($fields['UF_KPP'])){
            $APPLICATION->ThrowException(Loc::getMessage('HELPER_CHECK_LEGAL_ENTER_KPP'));
            return false;
        }
        if ($notUseKpp)
            $params['filter'] = array('UF_INN' => $fields['UF_INN']);
        else
            $params['filter'] = array('UF_INN' => $fields['UF_INN'], 'UF_KPP' => $fields['UF_KPP']);
        if(!empty(UserTable::getRow($params))){
            $errorCode = ($notUseKpp)? 'HELPER_CHECK_LEGAL_FIND_INN' : 'HELPER_CHECK_LEGAL_FIND_INN_KPP';
            $APPLICATION->ThrowException(Loc::getMessage($errorCode));
            return false;
        }
        return true;
    }
    /**
     * @param Order $order
     * @return bool
     * <p>проверяет нужно ли изменить цену в зависимости от способа оплаты</p>
     * <p>и изменяет цену в обработчике сохранения заказа,компоненте заказа</p>
     * <p>обработчик сохранения заказа так же контролит компонент изменения оплаты через флаг self::$REFRESH_PRICES</p>
     * <a href='local/php_interface/init.php::14'>Обработчик</a>
     * <a href='local/components/bitrix/sale.order.ajax/class.php::2317'>Компонент</a>
     * <a href='local/components/bitrix/sale.order.payment.change/class.php::471'>Изменение оплаты</a>
     */
    public static function checkNeedChangeProductPrice(Order $order){
        $userPriceId = CatalogHelper::getUserPriceId();
        $payment = self::getOrderPayment($order->getPaymentCollection());
        $priceId = $userPriceId;
        $needRefresh = self::$REFRESH_PRICES;
        self::$PRICE_CHANGED = false;
        if ($userPriceId === self::KP_PRICE_ID) return false;
        if (in_array((int)$payment->getPaymentSystemId(), self::PAYSYSTEMS_NEED_CHANGE_PRICE)){
            $priceId = self::KP_PRICE_ID;
            $needRefresh = true;
        }
        if (!$needRefresh) return false;
        $basket = $order->getBasket();
        $saveSum = (float)$payment->getSum() - (float)$basket->getPrice();
        /** @var BasketItem $basketItem */
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $basketItem){
            $id = $basketItem->getProductId();
            $priceArray = CatalogHelper::getProductPrice($id, $priceId);
            $basketItem->setPrice($priceArray['PRICE'], true);
            $basketItem->setField('BASE_PRICE', $priceArray['PRICE']);
            $basketItem->setField('PRODUCT_PRICE_ID', $priceArray['ID']);
            $basketItem->setField('PRICE_TYPE_ID', $priceId);
        }
        $payment->setField('SUM', ($saveSum + (float)$basket->getPrice()));
        self::$REFRESH_PRICES = false;
        return true;
    }
    /**
     * @param PaymentCollection $paymentCollection
     * @return Payment
     */
    public static function getOrderPayment(PaymentCollection $paymentCollection){
        /**@var $payment Payment*/
        foreach ($paymentCollection as $payment)
            if ($payment->isInner()) continue;
        return $payment;
    }
    public static function setNeedRefreshProductPrices(){
        self::$REFRESH_PRICES = true;
    }

    /**
     * @param string $userContact
     * @param int|null $productId
     */
    public static function setNeedSubscribeSanding(string $userContact = '', int $productId = null){
        if (empty($userContact) || empty($productId)) return;
        $param = array();
        $param['filter'] = array('USER_CONTACT' => $userContact, 'ITEM_ID' => $productId);
        $param['select'] = array('ID');
        try {
            $iterator = SubscribeTable::getList($param);
            while ($subscribe = $iterator->fetch()) {
                SubscribeTable::update((int)$subscribe['ID'], array('NEED_SENDING' => 'Y'));
            }
        }catch (\Exception $exception){
            return;
        }
    }
    public static function updateProperty(Order $order) {
        $propertyCollection = $order->getPropertyCollection();
        $couponProperty = null;
        $shipmentProperty = null;
        $couponPriceXMLIdProperty = null;
        if ((int)$order->getPersonTypeId() === 1){
            $couponProperty = $propertyCollection->getItemByOrderPropertyId(39);
            $couponPriceXMLIdProperty = $propertyCollection->getItemByOrderPropertyId(41);
            $shipmentProperty = $propertyCollection->getItemByOrderPropertyId(43);
        }
        elseif ((int)$order->getPersonTypeId() === 2){
            $couponProperty = $propertyCollection->getItemByOrderPropertyId(40);
            $couponPriceXMLIdProperty = $propertyCollection->getItemByOrderPropertyId(42);
            $shipmentProperty = $propertyCollection->getItemByOrderPropertyId(44);
        }
        if ($couponProperty instanceof PropertyValue){
            if (empty($couponProperty->getValue())){
                $couponName = '';
                $couponPriceXMLId = null;
                $discountData = $order->getDiscount()->getApplyResult();
                foreach ($discountData['COUPON_LIST'] as $coupon){
                    if (!empty($couponName)) $couponName .= ', ';
                    if ($coupon['APPLY'] === 'Y') {
                        $couponName .= $coupon['COUPON'];
                        $couponPriceXMLId = self::getDiscountPriceXml((int)$coupon['DATA']['DISCOUNT_ID']);
                    }
                }
                try {
                    if (!empty($couponName)) $couponProperty->setValue($couponName);
                    if (!empty($couponPriceXMLId)) $couponPriceXMLIdProperty->setValue($couponPriceXMLId);
                }catch (\Exception $e){}
            }
        }

        if ($shipmentProperty instanceof PropertyValue){
            try {
                $shipmentCollection = $order->getShipmentCollection();
                foreach ($shipmentCollection as $shipment){
                    if (empty($shipment->getStoreId()) && !($shipment instanceof Shipment)) continue;
                    $shipmentAddress = StoreTable::getRowById($shipment->getStoreId())['ADDRESS'];
                    if (!empty($shipmentAddress))
                        $shipmentProperty->setValue($shipmentAddress);
                }
            }catch (\Exception $e){}
        }
        return $order;
    }
    protected static function getDiscountPriceXml($discountId = null){
        if (empty($discountId)) return false;
        $application = DiscountTable::getRowById($discountId)['APPLICATION'];
        $callPos = strpos($application, 'setPriceGroupDiscount');
        if ($callPos === false) return false;
        $match = (int)substr($application, $callPos + 33, 2);
        if (empty($match)) return false;
        Loader::includeModule('catalog');
        $result = GroupTable::getRowById($match);
        return $result['XML_ID'];
    }
}
