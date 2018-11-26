<?php
namespace Caweb\Main\Sale;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
class DiscountTable extends Entity\DataManager {
    public static function getTableName(){
        return "b_caweb_discount";
    }
    public static function getConnectionName(){
        return 'default';
    }
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID',array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('KEYWORD',array(
                'required' => true
            )),
            new Entity\StringField('PRICE_ID',array(
                'required' => true
            )),
            new Entity\StringField('ACTIVE',array(
                'required' => true,
                'default_value' => 'N'
            )),
            new Entity\DatetimeField('ACTIVE_FROM',array(
                'required' => true,
                'default_value' => new Type\DateTime
            )),
            new Entity\DatetimeField('ACTIVE_TO',array(
                'required' => true
            )),
            new Entity\ReferenceField(
                'PRICE',
                '\Bitrix\Catalog\GroupTable',
                array('=this.PRICE_ID' => 'ref.ID'))
        );
    }

}