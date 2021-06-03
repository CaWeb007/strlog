<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/prolog.php");

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Catalog\GroupTable;
use Caweb\Main\Sale\DiscountTable;
use Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);
Loader::includeModule('catalog');
Loader::includeModule('caweb.main');

$bReadOnly = false;
if ($ex = $APPLICATION->GetException()) {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	$strError = $ex->GetString();
	ShowError($strError);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}
$strError = "";
$bVarsFromForm = false;
$arFields = array();
$ID = intval($ID);
$arCatalogGroups = array();
$rsCatalogGroups = GroupTable::getList(array('select' => array('ID', 'NAME')));
while ($arGroup = $rsCatalogGroups->fetch()) {
    $arCatalogGroups[] = array(
		'ID' => intval($arGroup['ID']),
		'NAME' => $arGroup['NAME'],
	);
}
$dbUserGroups = \Bitrix\Main\GroupTable::getList();
$arUserGroups = array();
while ($iterator = $dbUserGroups->fetch()){
    if (array_search((int)$iterator['ID'], \Caweb\Main\Catalog\Helper::SITE_GROUP_MODEL) !== false)
        $arUserGroups[] = $iterator;
}

if ('POST' == $_SERVER['REQUEST_METHOD'] && (strlen($save)>0 || strlen($apply)>0) && check_bitrix_sessid()) {
	$arGroupID = array();
	$arFields = array(
		'KEYWORD' => (isset($_POST['KEYWORD']) ? $_POST['KEYWORD'] : ''),
		'PRICE_ID' => isset($_POST['PRICE_ID']) ? $_POST['PRICE_ID'] : '',
        'ACTIVE' => (isset($_POST['ACTIVE']) && 'Y' == $_POST['ACTIVE'] ? 'Y' : 'N'),
        'ACTIVE_FROM' => (isset($_POST['ACTIVE_FROM']) ? DateTime::createFromUserTime($_POST['ACTIVE_FROM']) : ''),
        'ACTIVE_TO' => (isset($_POST['ACTIVE_TO']) ? DateTime::createFromUserTime($_POST['ACTIVE_TO']) : ''),
        'NO_RIGHTS' => serialize($_POST['NO_RIGHTS'])
	);
	$DB->StartTransaction();
	$result = array();
	if (0 < $ID) {
	    $result = DiscountTable::update($ID, $arFields);
		$bVarsFromForm = !$result->isSuccess();
	}else{
	    $result = DiscountTable::add($arFields);
		$ID = $result->getId();
		$bVarsFromForm = (!(0 < intval($ID)));
	}
	if (!$bVarsFromForm) {
		$DB->Commit();
		if (strlen($save)>0)
			LocalRedirect("caweb_discount_edit.php?lang=".LANGUAGE_ID);
		elseif (strlen($apply)>0)
			LocalRedirect("caweb_discount_edit.php?lang=".LANGUAGE_ID.'&ID='.$ID);
	}else{
		if ($ex = $result->getErrorMessages())
			$strError = array_shift($ex)."<br>";
		else
			$strError = (0 < $ID ? Loc::getMessage("ERROR_UPDATING_TYPE") : Loc::getMessage("ERROR_ADDING_TYPE"))."<br>";;
		$DB->Rollback();
	}
}
$boolActive = false;
$arDefaultValues = array(
    'KEYWORD' => '',
    'ACTIVE' => 'N'
);
$arSelect = array_merge(array('ID', 'ACTIVE_FROM', 'ACTIVE_TO', 'PRICE_ID', 'NO_RIGHTS'), array_keys($arDefaultValues));
$arFilter = array('ID' => $ID);
$arDiscount = array();
$arDiscount = DiscountTable::getRow(array('filter' => $arFilter, 'select' => $arSelect));
if (empty($arDiscount)) {
	$ID = 0;
    $arDiscount = $arDefaultValues;
}else{
	$boolActive = (0 < $ID && 'Y' == $arDiscount['ACTIVE']);
	$arDiscount['NO_RIGHTS'] = unserialize($arDiscount['NO_RIGHTS']);
}

if ($bVarsFromForm) {
    $arDiscount = $arFields;
}
$sDocTitle = ($ID>0) ? Loc::getMessage("CAT_EDIT_RECORD", array("#ID#" => $ID)) : Loc::getMessage("CAT_NEW_RECORD");
$APPLICATION->SetTitle($sDocTitle);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$aMenu = array(
	array(
		"TEXT" => Loc::getMessage("CGEN_2FLIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/caweb_discount_list.php?lang=".LANGUAGE_ID."&".GetFilterParams("filter_", false)
	)
);
if ($ID > 0 && !$bReadOnly) {
    $aMenu[] = array("SEPARATOR" => "Y");
    $aMenu[] = array(
        "TEXT" => Loc::getMessage("CGEN_NEW_GROUP"),
        "ICON" => "btn_new",
        "LINK" => "/bitrix/admin/caweb_discount_edit.php?lang=".LANGUAGE_ID."&".GetFilterParams("filter_", false)
    );
    if (!$boolActive) {
		$aMenu[] = array(
			"TEXT" => Loc::getMessage("CGEN_DELETE_GROUP"),
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".Loc::getMessage("CGEN_DELETE_GROUP_CONFIRM")."')) window.location='/local/admin/caweb_discount_edit.php?action=delete&ID[]=".$ID."&lang=".LANGUAGE_ID."&".bitrix_sessid_get()."#tb';",
			"WARNING" => "Y"
		);
	}
}
$context = new CAdminContextMenu($aMenu);
$context->Show();
if (!empty($strError))
	CAdminMessage::ShowMessage($strError);
?>
<form method="POST" action="<?=$APPLICATION->GetCurPage()?>?" name="catalog_edit">
    <?=GetFilterHiddens("filter_");?>
    <input type="hidden" name="Update" value="Y">
    <input type="hidden" name="lang" value="<?=LANGUAGE_ID ?>">
    <input type="hidden" name="ID" value="<?=$ID ?>">
    <?=bitrix_sessid_post()?>
    <?$aTabs = array(
        array("DIV" => "edit1", "TAB" => Loc::getMessage("CGEN_TAB_GROUP"), "ICON" => "catalog", "TITLE" => Loc::getMessage("CGEN_TAB_GROUP_DESCR"))
    );
    $tabControl = new CAdminTabControl("tabControl", $aTabs);
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    if ($ID>0):?>
        <tr>
            <td width="40%">ID:</td>
            <td width="60%"><?=$ID?></td>
        </tr>
    <?endif;?>
    <tr>
        <td width="40%"><?=Loc::getMessage("ACTIVE")?></td>
        <td width="60%">
                <input type="hidden" name="ACTIVE" value="N" />
                <input type="checkbox" id="ch_ACTIVE" name="ACTIVE" value="Y" <?echo ('Y' == $arDiscount['ACTIVE'] ? 'checked' : '')?>/>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=Loc::getMessage("KEYWORD")?></td>
        <td width="60%"><input size="30" type="text" name="KEYWORD" value="<?=htmlspecialcharsbx($arDiscount['KEYWORD'])?>"></td>
    </tr>
    <tr>
        <td width="40%"><?=Loc::getMessage("ACTIVE_FROM") ?></td>
        <td width="60%">
            <?=CAdminCalendar::CalendarDate(
                'ACTIVE_FROM',
                $arDiscount['ACTIVE_FROM'] instanceof DateTime ? $arDiscount['ACTIVE_FROM']->toString() : '',
                '17',
                true
            )?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=Loc::getMessage("ACTIVE_TO")?></td>
        <td width="60%">
        <?=CAdminCalendar::CalendarDate(
            'ACTIVE_TO',
            $arDiscount['ACTIVE_TO'] instanceof DateTime ? $arDiscount['ACTIVE_TO']->toString() : '',
            '17',
            true
        )?>
        </td>
    </tr>
    <tr>
        <td valign="top" width="40%">
            <?=Loc::getMessage('PRICE_ID')?>
        </td>
        <td width="60%">
            <select name="PRICE_ID" size="1">
            <?foreach ($arCatalogGroups as &$arOneGroup){?>
                <option value="<?=$arOneGroup["ID"]?>"<?if ($arOneGroup["ID"] == $arDiscount['PRICE_ID']) echo " selected"?>><? echo "[".$arOneGroup["ID"]."] ".htmlspecialcharsbx($arOneGroup["NAME"]); ?></option>
            <?}
            if (isset($arOneGroup)) unset($arOneGroup);?>
            </select>
        </td>
    </tr>
    <tr>
        <td valign="top" width="40%">
            <?=Loc::getMessage('NO_RIGHTS')?>
        </td>
        <td width="60%">
            <select name="NO_RIGHTS[]" multiple size="<?=count($arUserGroups)?>">
                <?foreach ($arUserGroups as $arOneUserGroup){?>
                    <option value="<?=(int)$arOneUserGroup["ID"]?>"<?if (array_search($arOneUserGroup["ID"], $arDiscount['NO_RIGHTS']) !== false) echo " selected"?>><? echo "[".$arOneUserGroup["ID"]."] ".htmlspecialcharsbx($arOneUserGroup["NAME"]); ?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <?
    $tabControl->EndTab();
    $tabControl->Buttons(
            array(
                    "disabled" => $bReadOnly,
                    "back_url" => "/bitrix/admin/caweb_discount_edit.php?lang=".LANGUAGE_ID."&".GetFilterParams("filter_", false)
                )
        );
    $tabControl->End();
    ?>
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>