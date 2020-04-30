<?
namespace Caweb\Main\Events;

use Bitrix\Main\UserTable;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaymentCollection;
use Caweb\Main\Catalog\Helper as CatalogHelper;

class Helper{
    const KP_PRICE_ID = 11;
    const PAYSYSTEMS_NEED_CHANGE_PRICE = array(10,13,14,15);//todo КОСТЫЛЬ КОНЕЧНО, но не забыть установить
    public static function notUniqueLegalUser($inn, $kpp){
        $params['filter'] = array('UF_INN' => $inn, 'UF_KPP' => $kpp);
        return (!empty(UserTable::getRow($params)));
    }
    /**
     * @param Order $order
     * @return bool
     * <p>проверяет нужно ли изменить цену в зависимости от способа оплаты</p>
     * <p>и изменяет цену в обработчике сохранения заказа и компоненте</p>
     * <a href='local/php_interface/init.php::14'>Обработчик</a>
     * <a href='local/components/bitrix/sale.order.ajax/class.php::2317'>Компонент</a>
     */
    public static function checkNeedChangeProductPrice(Order $order){
        $userPriceId = CatalogHelper::getUserPriceId();
        if ($userPriceId === self::KP_PRICE_ID) return false;
        $payment = self::getOrderPayment($order->getPaymentCollection());
        if (!in_array((int)$payment->getPaymentSystemId(), self::PAYSYSTEMS_NEED_CHANGE_PRICE)) return false;
        $basket = $order->getBasket();
        $saveSum = (float)$payment->getSum() - (float)$basket->getPrice();
        /** @var BasketItem $basketItem */
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $basketItem){
            $id = $basketItem->getProductId();
            $priceArray = CatalogHelper::getProductPrice($id, self::KP_PRICE_ID);
            $basketItem->setPrice($priceArray['PRICE'], true);
            $basketItem->setField('BASE_PRICE', $priceArray['PRICE']);
            $basketItem->setField('PRODUCT_PRICE_ID', $priceArray['ID']);
            $basketItem->setField('PRICE_TYPE_ID', self::KP_PRICE_ID);
        }
        $payment->setField('SUM', ($saveSum + (float)$basket->getPrice()));
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
}
