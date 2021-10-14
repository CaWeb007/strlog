<?php


namespace Caweb\Main\Catalog;


use Bitrix\Iblock\Component\ElementList;

class CustomElementList extends ElementList {
    protected function parseConditionName(array $condition)
    {
        $name = '';
        $conditionNameMap = array(
            'CondIBXmlID' => 'XML_ID',
            'CondIBElement' => 'ID',
//			'CondIBActive' => 'ACTIVE',
            'CondIBSection' => 'SECTION_ID',
            'CondIBDateActiveFrom' => 'DATE_ACTIVE_FROM',
            'CondIBDateActiveTo' => 'DATE_ACTIVE_TO',
            'CondIBSort' => 'SORT',
            'CondIBDateCreate' => 'DATE_CREATE',
            'CondIBCreatedBy' => 'CREATED_BY',
            'CondIBTimestampX' => 'TIMESTAMP_X',
            'CondIBModifiedBy' => 'MODIFIED_BY',
            'CondIBTags' => 'TAGS',
            'CondCatQuantity' => 'CATALOG_QUANTITY',
            'CondCatWeight' => 'CATALOG_WEIGHT'
        );

        if (isset($conditionNameMap[$condition['CLASS_ID']]))
        {
            $name = $conditionNameMap[$condition['CLASS_ID']];
        }
        elseif (strpos($condition['CLASS_ID'], 'CondIBProp') !== false)
        {
            $name = $condition['CLASS_ID'];
        }

        return $name;
    }
}