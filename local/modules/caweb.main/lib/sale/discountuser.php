<?php
namespace Caweb\Main\Sale;

use Bitrix\Main\Entity;
use Bitrix\Main\Result;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Fuser;
use Caweb\Main\Catalog\Helper;

Loc::loadMessages(__FILE__);

class DiscountUserTable extends Entity\DataManager {
    public static function getTableName(){
        return "b_caweb_discount_user";
    }
    public static function getConnectionName(){
        return 'default';
    }
    public static function getMap(){
        return array(
            new Entity\IntegerField('ID',array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('DISCOUNT_ID',array(
                'required' => true,
            )),
            new Entity\StringField('USER_ID',array(
                'required' => true,
                'default_value' => '0'
            )),
            new Entity\StringField('FUSER_ID', array(
                'required' => true,
                'default_value' => '0'
            )),
            new Entity\StringField('STATUS', array(
                'required' => true
            )),
            new Entity\ReferenceField(
                'DISCOUNT',
                '\Caweb\Main\Sale\DiscountTable',
                array('=this.DISCOUNT_ID' => 'ref.ID'))
        );
    }
    public static function deleteUsers($discount_id){
        $params['filter'] = array('DISCOUNT_ID' => $discount_id);
        $db = self::getList($params);
        while ($array = $db->fetch()){
            if (empty($array['ID'])) continue;
            self::delete($array['ID']);
        }
    }
    public static function getUserDiscount($status = false, $actualTime = false, $discountId = false, $keyword = false){
        $param = array();
        if ($actualTime){
            $date = new Type\DateTime();
            $strFrom = "<=DISCOUNT.ACTIVE_FROM";
            $strTo = ">DISCOUNT.ACTIVE_TO";
            $param['filter'][$strFrom] = $date;
            $param['filter'][$strTo] = $date;
        }
        global $USER;
        $userId = $USER->GetID();
        $result = array();
        if (!empty($userId)){
            $param['filter']['USER_ID'] = $userId;
        }else{
            $param['filter']['FUSER_ID'] = Fuser::getId();
        }
        if (!empty($status))
            $param['filter']['STATUS'] = $status;

        if (!empty($discountId))
            $param['filter']['DISCOUNT_ID'] = $discountId;
        if (!empty($keyword))
            $param['filter']['DISCOUNT.KEYWORD'] = $keyword;
        $param['select'] = array('COUPON_ID' => 'ID','STATUS','KEYWORD'=> 'DISCOUNT.KEYWORD','PRICE_ID'=> 'DISCOUNT.PRICE_ID', 'DISCOUNT_ID');
        $userPrice = Helper::getUserPriceId();
        $db = self::getList($param);
        while ($array = $db->fetch()){
            $result[$array['KEYWORD']] = $array;
            $result[$array['KEYWORD']]['USER_PRICE_ID'] = $userPrice;
        }
        if (empty($result)) return false;
        return $result;
    }
    public static function addDiscount($coupon){
        if (empty($coupon['KEYWORD'])) return false;
        $user = '0';
        global $USER;
        if ((isset($USER) && $USER instanceof \CUser) && $USER->IsAuthorized())
            $user = $USER->GetID();
        $fuser = '0';
        $fuser = Fuser::getId();
        $status = $coupon['STATUS'];
        if (empty($status))
            $status = DiscountManager::STATUS_START;
        $discountID = $coupon['DISCOUNT_ID'];
        if (empty($discountID))
            $discountID = self::getUserDiscount($coupon['KEYWORD'])['DISCOUNT_ID'];
        $arFields = array(
            'DISCOUNT_ID' => $discountID,
            'USER_ID' => $user,
            'FUSER_ID' => $fuser,
            'STATUS' => $status
        );
        $res = self::add($arFields);
        if (($res instanceof Result) && $res->isSuccess()){
            return $res->getId();
        }else{
            return false;
        }
    }
    public static function deleteDiscount($coupon){
        $couponId = array($coupon['COUPON_ID']);
        $discountId = $coupon['DISCOUNT_ID'];
        $keyword = $coupon['KEYWORD'];
        $arDiscount = array();

        if (empty($couponId) && !empty($keyword))
            $arDiscount = self::getUserDiscount(DiscountManager::STATUS_ENTER, true, false, $keyword);

        if (empty($couponId) && !empty($discountId))
            $arDiscount = self::getUserDiscount(DiscountManager::STATUS_ENTER, true, $discountId);

        if (empty($couponId) && !empty($arDiscount))
            foreach ($arDiscount as $item) $couponId[] = $item['ID'];

        if (empty($couponId)) return false;
        foreach ($couponId as $id) {
            $res = self::delete($id);
        }

    }
    public static function getByDiscountId($discountId){
        $params['filter']['DISCOUNT_ID'] = $discountId;
    }
}