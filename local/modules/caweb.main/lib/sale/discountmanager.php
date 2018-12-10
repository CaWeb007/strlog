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
    const STATUS_END = 'END';
    const STATUS_NOT_FOUND = 'NOT_FOUND';
    const STATUS_MAX_DISCOUNT = 'MAX_DISCOUNT';
    const SESSION_KEY = 'CAWEB_DISCOUNT';
    const DEFAULT_PRICE = 14;
    public static $jsText = array();
    static $processedCoupon = array();
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
            if (!empty($status) && $status !== self::STATUS_START) return false;
        }
        $coupon = array();
        $coupon['KEYWORD'] = $keyword;
        $price = DiscountTable::getUserDiscountPrice($keyword);
        if (!$price) {
            $coupon['STATUS'] = self::STATUS_NOT_FOUND;
            return $coupon;
        }
        $old_price = '';
        list($price,$old_price) = self::checkPrice($price);
        if (!$price) {
            $coupon['STATUS'] = self::STATUS_MAX_DISCOUNT;
            return $coupon;
        }
        $coupon['STATUS'] = self::STATUS_START;
        $coupon['PRICE_ID'] = $price;
        $coupon['USER_PRICE_ID'] = $old_price;
        return $coupon;

    }
    static function checkPrice($price){
        if (!$price) return array('','');
        $old_price = Helper::getUserPriceId();
        if ($old_price) $old_price = self::DEFAULT_PRICE;
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
        return;
        $session = $_SESSION[self::SESSION_KEY];
        if (empty($session)) return false;
        $result = array();
        foreach ($session as $discount){
            $result['COUPON'] = $discount['KEYWORD'];
            if ($session['STATUS'] === self::STATUS_START)
                $result['JS_STATUS'] = 'ENTERED';
            $result['JS_CHECK_CODE'] = static::$jsText[$discount['KEYWORD']];
        }
        return $result;
    }
    public static function changeDiscount(){
        if (empty(static::$processedCoupon)) return false;
    }
    public static function discountProcess($item){
        return false;
        $result = $item;







        if (empty($_SESSION[self::SESSION_KEY])) return false;
        $session = $_SESSION[self::SESSION_KEY];
        $price = 0;
        $currency = '';
        $keyword = '';
        foreach ($session as $discount){
            if ($discount['PRICE'] === $result['PRICE_TYPE_ID']){
                static::$jsText[$discount['KEYWORD']] = 'test1';
                continue;
            }
            $param['filter'] = array(
                'PRODUCT_ID' => $result['PRODUCT_ID'],
                'CATALOG_GROUP_ID' => $discount['PRICE']
            );
            $param['select'] = array('ID','PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
            $arPrice = PriceTable::getRow($param);
            if (empty($price) || ($arPrice['PRICE'] < $price)){
                $price = $arPrice['PRICE'];
                $currency = $arPrice['CURRENCY'];
                $keyword = $discount['KEYWORD'];
            }
        }

        $discountPrice = $result['BASE_PRICE'] - $price;

        if (($result['PRICE'] <= $price) || ($discountPrice <= 0)){
            static::$jsText[$keyword] = 'test2';
            return $result;
        }
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
}