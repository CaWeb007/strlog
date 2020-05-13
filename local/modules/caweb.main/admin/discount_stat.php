<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/prolog.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Caweb\Main\Sale\DiscountTable;
use Caweb\Main\Sale\DiscountUserTable;

Loader::includeModule('caweb.main');

Loc::loadMessages(__FILE__);

global $APPLICATION, $DB;

$bReadOnly = true;
if ($ex = $APPLICATION->GetException()){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    $strError = $ex->GetString();
    ShowError($strError);
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    die();
}
$sTableID = "tbl_caweb_discount_stat";
$lAdmin = new CAdminList($sTableID, $oSort);
$arFilter = array();
$lAdmin->AddHeaders(array(
    array(
        "id" => "ORDER",
        "content" => Loc::getMessage('DISCOUNT_STAT_ORDER'),
        "sort" => "",
        "default" => true
    )
));
$arSelectFields = $lAdmin->GetVisibleHeaderColumns();
$dbResultList = DiscountUserTable::getList(array('filter'=> array('STATUS' => 'APPLY', 'DISCOUNT_ID' => (int)$ID)));
$dbResultList = new CAdminResult($dbResultList, $sTableID);
$strNameFormat = CSite::GetNameFormat(true);
while ($arRes = $dbResultList->Fetch()) {
    $arRes['ID'] = (int)$arRes['ID'];
    $row = &$lAdmin->AddRow($arRes['ID'], $arRes);
    $row->AddViewField("ORDER",
        '<a href="/bitrix/admin/sale_order_view.php?ID='.$arRes["ORDER_ID"].'">'.$arRes["ORDER_ID"].'</a>');
}

$lAdmin->CheckListMode();
$APPLICATION->SetTitle(Loc::getMessage('DISCOUNT_STAT_TITLE', array('#NAME#' => DiscountTable::getRowById((int)$ID)['KEYWORD'])));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>