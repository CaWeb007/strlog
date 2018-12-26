<?php
namespace Caweb\Main\Sale;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Caweb\Main\Log\Write;

Loc::loadMessages(__FILE__);

class DiscountTable extends Entity\DataManager {
    public static function getTableName(){
        return "b_caweb_discount";
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
            new Entity\StringField('KEYWORD',array(
                'required' => true,
                'title' => Loc::getMessage('TITLE_KEYWORD'),
                'validation' => function(){
                    return array(
                        new Entity\Validator\Unique
                    );
                }
            )),
            new Entity\StringField('PRICE_ID',array(
                'required' => true,
                'title' => Loc::getMessage('TITLE_PRICE_ID')
            )),
            new Entity\StringField('ACTIVE',array(
                'required' => true,
                'default_value' => 'N'
            )),
            new Entity\DatetimeField('ACTIVE_FROM',array(
                'required' => true,
                'default_value' => new Type\DateTime,
                'title' => Loc::getMessage('TITLE_ACTIVE_FROM')
            )),
            new Entity\DatetimeField('ACTIVE_TO',array(
                'required' => true,
                'title' => Loc::getMessage('TITLE_ACTIVE_TO'),
                'validation' => function(){
                    return array(
                        array(__CLASS__, 'dataValidator')
                    );
                }
            )),
            new Entity\ReferenceField(
                'PRICE',
                '\Bitrix\Catalog\GroupTable',
                array('=this.PRICE_ID' => 'ref.ID')),

        );
    }
    public static function dataValidator($value, $primary, $row, $field){
        $dataFrom = $row['ACTIVE_FROM'];
        $dataTo = $row['ACTIVE_TO'];
        $thisTime = new Type\DateTime();
        $timestampFrom = $dataFrom->getTimestamp();
        $timestampTo = $dataTo->getTimestamp();
        $timestampThis = $thisTime->getTimestamp();
        $copyData = clone $dataFrom;
        $copyData->add("1 day");
        $timestampDay = $copyData->getTimestamp();
        if ($timestampThis > $timestampFrom) return Loc::getMessage('DATA_FROM_ERROR');
        if ($timestampFrom > $timestampTo) return Loc::getMessage('DATA_TO_FROM_ERROR');
        if ($timestampDay > $timestampTo) return Loc::getMessage('DATA_TO_ERROR');
        return true;
    }
    public static function deleteDiscount($discount_id){
        if (empty($discount_id)) return;
        DiscountUserTable::deleteUsers($discount_id);
        return self::delete($discount_id);
    }
    public static function getUserDiscount($keyword){
        $result = array();
        $date = new Type\DateTime();
        $arDiscount = DiscountUserTable::getUserDiscount(DiscountManager::STATUS_APPLY, true, false, $keyword);
        if ($arDiscount) return false;
        $param['filter'] = array(
            '!ID' => $arDiscount,
            'KEYWORD' => $keyword,
            '<=ACTIVE_FROM' => $date,
            '>ACTIVE_TO' => $date
        );
        $param['select'] = array('ID', 'PRICE_ID');
        $result = self::getRow($param);
        return $result;
    }

}