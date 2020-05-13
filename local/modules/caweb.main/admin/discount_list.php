<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/prolog.php");

use Bitrix\Main\Loader;
use Caweb\Main\Sale\DiscountTable;
use Bitrix\Main\Localization\Loc;
use Caweb\Main\Sale\DiscountUserTable;

Loader::includeModule('catalog');
Loader::includeModule('caweb.main');

Loc::loadMessages(__FILE__);

global $APPLICATION, $DB;

$bReadOnly = false;
if ($ex = $APPLICATION->GetException()){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    $strError = $ex->GetString();
    ShowError($strError);
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    die();
}
$sTableID = "tbl_caweb_discount_list";
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);
$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();
if ($lAdmin->EditAction()){
    foreach ($_POST['FIELDS'] as $ID => $arFields){
        $ID = (int)$ID;
        if ($ID <= 0 || !$lAdmin->IsUpdated($ID)) continue;
        $DB->StartTransaction();
        if (!DiscountTable::update($ID, $arFields)->isSuccess()){
            if ($ex = $APPLICATION->GetException())
                $lAdmin->AddUpdateError($ex->GetString(), $ID);
            else
                $lAdmin->AddUpdateError(Loc::getMessage("ERROR_UPDATING_REC")." (".$arFields["KEYWORD"].")", $ID);
            $DB->Rollback();
        }else{
            $DB->Commit();
        }
    }
}

if (($arID = $lAdmin->GroupAction())) {
    if ($_REQUEST['action_target']=='selected') {
        $arID = Array();
        $dbResultList = DiscountTable::getList(array('select' => array('ID')));
        while ($arResult = $dbResultList->fetch())
            $arID[] = $arResult['ID'];
    }
    foreach ($arID as $ID) {
        if (strlen($ID) <= 0) continue;
        switch ($_REQUEST['action']) {
            case "delete":
                @set_time_limit(0);
                $DB->StartTransaction();
                if (!DiscountTable::deleteDiscount($ID)->isSuccess()) {
                    $DB->Rollback();
                    if ($ex = $APPLICATION->GetException())
                        $lAdmin->AddGroupError($ex->GetString(), $ID);
                    else
                        $lAdmin->AddGroupError(Loc::getMessage("ERROR_DELETING_TYPE"), $ID);
                }else{
                    $DB->Commit();
                }
                break;
        }
    }
}

$lAdmin->AddHeaders(array(
    array(
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "default" => true
    ),
    array(
        "id" => "KEYWORD",
        "content" => Loc::getMessage("KEYWORD"),
        "sort" => "",
        "default" => true
    ),
    array(
        "id" => "PRICE_ID",
        "content" => Loc::getMessage('PRICE'),
        "sort" => "",
        "default" => true
    ),
    /*array(
        "id" => "ACTIVE",
        "content" => Loc::getMessage("ACTIVE"),
        "sort" => "",
        "default" => true
    ),*/
    array(
        "id" => "ACTIVE_FROM",
        "content" => Loc::getMessage("ACTIVE_FROM"),
        "sort" => "",
        "default" => true
    ),
    array(
        "id" => "ACTIVE_TO",
        "content" => Loc::getMessage("ACTIVE_TO"),
        "sort" => "",
        "default" => true
    ),
    array(
        "id" => "ORDERS_COUNT",
        "content" => 'количество заказов',
        "sort" => "",
        "default" => true
    )
));

$arSelectFieldsMap = array(
    "ID" => false,
    "KEYWORD" => false,
    "PRICE" => false,
    "ACTIVE" => false,
    "ACTIVE_FROM" => false,
    "ACTIVE_TO" => false
);
$arSelectFields = $lAdmin->GetVisibleHeaderColumns();
$arSelectFields = array_values($arSelectFields);
$arSelectFieldsMap = array_merge($arSelectFieldsMap, array_fill_keys($arSelectFields, true));
$arSelectFields['PRICE_NAME'] = 'PRICE.NAME';
$arSelectFields = array_diff($arSelectFields, array('ORDERS_COUNT'));
$dbResultList = DiscountTable::getList(array('select'=> $arSelectFields));
$dbResultList = new CAdminResult($dbResultList, $sTableID);
$arUserList = array();
$arUserID = array();
$strNameFormat = CSite::GetNameFormat(true);
$arRows = array();
while ($arRes = $dbResultList->Fetch()) {
    $arRes['ID'] = (int)$arRes['ID'];
    $arRows[$arRes['ID']] = $row = &$lAdmin->AddRow($arRes['ID'], $arRes);
    $row->AddViewField("ID",
        '<a href="/bitrix/admin/caweb_discount_edit.php?lang='.LANGUAGE_ID.'&ID='.$arRes["ID"].'&'.GetFilterParams("filter_").'">'.$arRes["ID"].'</a>');
    if ($arSelectFieldsMap['KEYWORD'])
        $row->AddInputField("KEYWORD", array("size" => 30));
    if ($arSelectFieldsMap['PRICE_ID'])
        $row->AddViewField("PRICE_ID", $arRes['PRICE_NAME']);
    if ($arSelectFieldsMap['ACTIVE'])
        $row->AddViewField("ACTIVE", ("Y" == $arRes['ACTIVE'] ? Loc::getMessage("ACTIVE_YES") : "&nbsp;"));
    if ($arSelectFieldsMap['ACTIVE_FROM'])
        $row->AddInputField("ACTIVE_FROM", array("size" => 30));
    if ($arSelectFieldsMap['ACTIVE_TO'])
        $row->AddInputField("ACTIVE_TO", array("size" => 30));

    $row->AddField("ORDERS_COUNT", DiscountUserTable::getDiscountOrdersCount((int)$arRes['ID']));

    $arActions = array();
    $arActions[] = array(
        "ICON" => "edit",
        "TEXT" => Loc::getMessage("EDIT_STATUS_ALT"),
        "ACTION" => $lAdmin->ActionRedirect("/bitrix/admin/caweb_discount_edit.php?ID=".$arRes['ID']."&lang=".LANGUAGE_ID."&".GetFilterParams("filter_").""),
        "DEFAULT" => true
    );
    $arActions[] = array(
        "ICON" => "view",
        "TEXT" => Loc::getMessage("STAT_LINK"),
        "ACTION" => $lAdmin->ActionRedirect("/bitrix/admin/caweb_discount_stat.php?ID=".$arRes['ID'].""),
        "DEFAULT" => true
    );
    $arActions[] = array(
        "SEPARATOR" => true
    );
    $arActions[] = array(
        "ICON" => "delete",
        "TEXT" => Loc::getMessage("DELETE_STATUS_ALT"),
        "ACTION" => "if(confirm('".GetMessageJS('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($arRes['ID'], "delete")
    );
    $row->AddActions($arActions);
}
$lAdmin->AddFooter(
    array(
        array(
            "title" => Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"),
            "value" => $dbResultList->SelectedRowsCount()
        ),
        array(
            "counter" => true,
            "title" => Loc::getMessage("MAIN_ADMIN_LIST_CHECKED"),
            "value" => "0"
        ),
    )
);
$lAdmin->AddGroupActionTable(
    array(
        "delete" => Loc::getMessage("MAIN_ADMIN_LIST_DELETE"),
    )
);
$aContext = array(
    array(
        "TEXT" => Loc::getMessage("CGAN_ADD_NEW"),
        "ICON" => "btn_new",
        "LINK" => "caweb_discount_edit.php?lang=".LANG,
        "TITLE" => Loc::getMessage("CGAN_ADD_NEW_ALT")
    ),
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();
$APPLICATION->SetTitle(Loc::getMessage("GROUP_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>