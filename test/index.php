<?

use Bitrix\Main\Loader;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Catalog\Ratio;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?
/*$param['filter'] = array('GROUP_ID' => 14);
$param['select'] = array('USER_ID','GROUP_ID');
$db = UserGroupTable::getList($param);
$account = new \CSaleUserAccount();
while ($ar = $db->fetch()){
    $accountFields = $account->GetByUserID($ar["USER_ID"], "RUB");
    $b = (float)$accountFields["CURRENT_BUDGET"];
    if($b === (float)0) continue;
    $id = $accountFields['ID'];
    Pr($accountFields);
    $account->Update($id, array("CURRENT_BUDGET" => 0.0000));
}*/
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