<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 08.05.2019
 * Time: 21:03
 */

namespace Caweb\Main\Sale;


use Bitrix\Sale\Payment;

class Tax{
    public static function unsetTax(Payment $payment){
        /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
        $paymentCollection = $payment->getCollection();
        /** @var \Bitrix\Sale\Order $order */
        $order = $paymentCollection->getOrder();
        $tax = $order->getTax();
        $newTax = $tax->getTaxList();
        if (is_array($newTax)){
            foreach ($newTax as $key => $value){
                $newTax[$key]['VALUE_MONEY'] = 0;
            }
        }elseif (!empty($newTax['VALUE_MONEY'])){
            $newTax['VALUE_MONEY'] = 0;
        }
        $tax->initTaxList($newTax);
    }
}