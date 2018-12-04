<?php
namespace Caweb\Main\Sale;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

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
            ))
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
    public static function getUserDiscount(){
        global $USER;
        $result = array();
        $param['filter'] = array('USER_ID' => $USER->GetID());
        $db = self::getList($param);
        while ($array = $db->fetch()){
            $result[] = $array['DISCOUNT_ID'];
        }
        if (empty($result)) return false;
        return $result;
    }

}