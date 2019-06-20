<?

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Catalog\Ratio;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?
/*Loader::includeModule('iblock');
$filter = array('IBLOCK_ID' => 16, 'ID' => array(21668,21669));
$select = array('ID','IBLOCK_ID','PROPERTY_NELZYA_OPLACHIVAT_BONUSAMI');
$res = CIBlockElement::GetList(array(), $filter, false, false, $select);
while($ar_fields = $res->GetNext()){
    Pr($ar_fields);
}*/
//Ratio::getInstance()->measureRatioConfig(Ratio::LINO_SECTION);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>