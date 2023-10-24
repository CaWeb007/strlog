<?
namespace Caweb\Main\Agent;

use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;

//\Caweb\Main\Agent\Skeleton::
class Skeleton {
    public static function setLinoSKUAvailable(){
        Loader::includeModule('iblock');
        Loader::includeModule('catalog');
        $yesId = 16878;
        $filter = array('IBLOCK_ID' => 16, 'SECTION_ID' => 2147, 'ACTIVE' => 'Y');
        $select = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_NOT_AVAILABLE_SKU','CATALOG_QUANTITY');
        $db = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($ar = $db->Fetch()){
            $skuFields = array('ID', 'CATALOG_QUANTITY');
            $skuFilter = array();
            $sku = \CCatalogSku::getOffersList($ar['ID'], 0, $skuFilter, $skuFields);
            $currentEnumId = $ar['PROPERTY_NOT_AVAILABLE_SKU_ENUM_ID'];
            $currentQuant = (float)$ar['CATALOG_QUANTITY'];
            $quant = 0;
            foreach ($sku[(int)$ar['ID']] as $item){
                $quant += (float)$item['CATALOG_QUANTITY'];
            }
            $update = false;
            if (($quant > 0.0) && (!empty($currentEnumId)))
                $update = null;
            if (($quant === 0.0) && (empty($currentEnumId))){
                $update = $yesId;
            }
            if ($update !== false)
                \CIBlockElement::SetPropertyValuesEx($ar['ID'], 16, array(
                    'NOT_AVAILABLE_SKU' => $update
                ));
            if ($quant !== $currentQuant)
                \CCatalogProduct::Update((int)$ar['ID'], array('QUANTITY' => $quant));
        }
        return '\Caweb\Main\Agent\Skeleton::setLinoSKUAvailable();';
    }
}