<?php
namespace Caweb\Main\Sale;

use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Diag\Debug;
use Caweb\Main\Catalog\Helper;

class DiscountManager{
    const STATUS_START = 'START';
    const STATUS_ENTER = 'ENTER';
    const STATUS_APPLY = 'APPLY';
    const STATUS_NEED_DELETE = 'NEED_DELETE';
    const STATUS_DELETE = 'DELETE';
    const STATUS_END = 'END';
    const STATUS_NOT_FOUND = 'NOT_FOUND';
    const STATUS_MAX_DISCOUNT = 'MAX_DISCOUNT';
    const SESSION_KEY = 'CAWEB_DISCOUNT';
    const DEFAULT_PRICE = 11;
    public static $jsText = array();
    public static $processedCoupon = array();
    public static function add($keyword){
        $coupon = self::checkInSession($keyword);
        if (!$coupon) return false;
        if ($coupon['error']) return $coupon['error'];
        $_SESSION[self::SESSION_KEY][$keyword] = $coupon;
        self::$processedCoupon = $coupon;
        return true;
    }
    public static function delete($keyword){
        $session = $_SESSION[self::SESSION_KEY][$keyword];
        if (!$session) return false;
        $status = $session['STATUS'];
        if ($status === self::STATUS_APPLY)
            return array('error' => 'tetereres');
        if ($status === self::STATUS_DELETE)
            return false;
        $coupon = $session;
        $coupon['STATUS'] = self::STATUS_NEED_DELETE;
        $_SESSION[self::SESSION_KEY][$keyword] = $coupon;
        self::$processedCoupon = $coupon;
        return true;
    }
    static function checkInSession($keyword){
        $session = $_SESSION[self::SESSION_KEY][$keyword];
        if ($session) {
            $status = $session['STATUS'];
            if (
                !empty($status)
                && ($status !== self::STATUS_START) && ($status !== self::STATUS_DELETE)
            )
                return false;
        }
        $coupon = array();
        $coupon['KEYWORD'] = $keyword;
        $price = DiscountTable::getUserDiscountPrice($keyword);
        if (!$price) {
            /*$coupon['STATUS'] = self::STATUS_NOT_FOUND;
            return $coupon;*/
            return false;
        }
        $old_price = '';
        list($price,$old_price) = self::checkPrice($price);

        if (!$price) {
            //$coupon['STATUS'] = self::STATUS_MAX_DISCOUNT;
            //return $coupon;
            return array('error' => 'tetereres');
        }
        $coupon['STATUS'] = self::STATUS_START;
        $coupon['PRICE_ID'] = $price;
        $coupon['USER_PRICE_ID'] = $old_price;
        return $coupon;

    }
    public static function checkPrice($price){
        $price = intval($price);
        if (empty($price)) return array('','');
        $old_price = Helper::getUserPriceId();
        if (empty($old_price)) $old_price = self::DEFAULT_PRICE;
        if (!$old_price) return array('','');
        $arPrices = array();
        $session = $_SESSION[self::SESSION_KEY];
        if (!empty($session)){
            foreach ($session as $coupon){
                $status = $coupon['STATUS'];
                if (($status[self::STATUS_ENTER]) || ($status[self::STATUS_APPLY]))
                    $arPrices[] = $coupon['PRICE_ID'];
            }
        }
        $arPrices = array_merge($arPrices, array($old_price,$price));
        $minPrice = Helper::getMinCatalogPrice($arPrices);
        if ($minPrice === $price) return array($price,$old_price);
        return array('','');
    }

    public static function getDiscountInfo(){
        $session = $_SESSION[self::SESSION_KEY];
        if (empty($session)) return false;
        $result = array();
        if (!empty(static::$processedCoupon))
            $result['COUPON'] = static::$processedCoupon['KEYWORD'];

        foreach ($session as $discount){
            if (!in_array($discount['STATUS'],array(
                self::STATUS_APPLY,self::STATUS_ENTER,
            ))) continue;
            $list = array();
            $list['COUPON'] = $discount['KEYWORD'];
            if ($session['STATUS'] === self::STATUS_ENTER)
                $list['JS_STATUS'] = 'ENTERED';
            if ($session['STATUS'] === self::STATUS_APPLY)
                $list['JS_STATUS'] = 'APPLYED';
            $list['JS_CHECK_CODE'] = 'test';
            $result['COUPON_LIST'][] = $list;
        }
        return $result;
    }
    public static function changeDiscount(){
        return !empty(static::$processedCoupon);
    }
    public static function discountProcess($item){
        $result = $item;
        $discount = static::$processedCoupon;
        /*Debug::dumpToFile(array(
            'discount' => $discount,
            'itemPrice' => (int)$item['PRICE_TYPE_ID'],
            'logic' => $discount['STATUS'] === self::STATUS_NEED_DELETE
        ),'process','caweb.log');*/
        if (empty($discount)) return false;
        $status = $discount['STATUS'];

        if (empty($status) || (($status !== self::STATUS_START) && ($status !== self::STATUS_NEED_DELETE)))
            return false;
        $boolDelete = $discount['STATUS'] === self::STATUS_NEED_DELETE;
        $priceID = $discount['PRICE_ID'];
        if ($boolDelete) $priceID = $discount['USER_PRICE_ID'];
        Debug::dumpToFile($item['PRICE_TYPE_ID'],'readPriceId','caweb.log');
        if (empty($priceID) || ((int)$priceID === (int)$item['PRICE_TYPE_ID']))
            return false;
        $param['filter'] = array(
            'PRODUCT_ID' => $result['PRODUCT_ID'],
            'CATALOG_GROUP_ID' => $priceID
        );
        $param['select'] = array('ID','PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
        $arPrice = PriceTable::getRow($param);
        /*Debug::dumpToFile(array(
            'delete' => $boolDelete,
            'priceId' => $priceID,
            'arPrice' => $arPrice
        ),'process','caweb.log');*/
        if (empty($arPrice)) return false;
        $price = $arPrice['PRICE'];
        $currency = $arPrice['CURRENCY'];

        $discountPrice = $result['BASE_PRICE'] - $price;

        $result['PRICE'] = $price;
        $result["DISCOUNT_PRICE"] = $discountPrice;
        $result["SUM_DISCOUNT_PRICE"] = $price;
        $result["SUM_VALUE"] = $price * $result['QUANTITY'];
        $result["SUM_FULL_PRICE"] = $result["BASE_PRICE"] * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE"] = $discountPrice * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["SUM_DISCOUNT_PRICE"],$currency);
        $result["DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["DISCOUNT_PRICE"],$currency);
        $result["PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["PRICE"],$currency);
        $result["SUM"] = \CCurrencyLang::CurrencyFormat($result["SUM_VALUE"],$currency);
        $result['PRICE_TYPE_ID'] = $arPrice['CATALOG_GROUP_ID'];
        return $result;
    }
    public static function changeStatus(){
        $result = '';
        $status = static::$processedCoupon['STATUS'];
        $keyword = static::$processedCoupon['KEYWORD'];
        if ($status === self::STATUS_START) $result = self::STATUS_ENTER;
        if ($status === self::STATUS_NEED_DELETE) $result = self::STATUS_DELETE;
        if ($status === self::STATUS_ENTER) $result = self::STATUS_APPLY;
        $_SESSION[self::SESSION_KEY][$keyword]['STATUS'] = $result;
    }
}