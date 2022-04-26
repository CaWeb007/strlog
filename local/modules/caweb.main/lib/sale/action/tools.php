<?php
namespace Caweb\Main\Sale\Action;

use Bitrix\Main\Entity;

class Tools {
    private static $customDiscountInfo = array();
    public static function getPhoneLink($phone){
        if (empty($phone)) return '';
        $result = str_replace(['(', ')', '-', ' '], '', $phone);
        $result = 'tel:'.substr_replace($result, '+7', 0, 1);
        return $result;
    }
    public static function setCustomDiscountCouponInfo($discountId, $arInfo){
        self::$customDiscountInfo[(int)$discountId] = $arInfo;
    }
    public static function getCustomDiscountCouponInfo($discountId){
        $result = self::$customDiscountInfo[(int)$discountId];
        unset(self::$customDiscountInfo[$discountId]);
        return $result;
    }
    public static function DiscountCondController($event){
        $result = new Entity\EventResult;
        $unpack = $event->getParameter('fields')['UNPACK'];
        $id = $event->getParameter('id')['ID'];
        if (strpos($unpack, '%STR_DISCOUNT_ID%') !== false)
            $unpack = str_replace('%STR_DISCOUNT_ID%', $id, $unpack);
        $result->modifyFields(array('UNPACK' => $unpack));
        return $result;
    }
}