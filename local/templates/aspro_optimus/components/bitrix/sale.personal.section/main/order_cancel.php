<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_CHAIN_ORDER_DETAIL", array("#ID#" => $arResult["VARIABLES"]["ID"])));

// $APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ORDERS"), $arResult['PATH_TO_ORDERS']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ORDER_DETAIL", array("#ID#" => $arResult["VARIABLES"]["ID"])));?>
<div class="personal_wrapper">
	<?$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.order.cancel",
		"",
		array(
			"PATH_TO_LIST" => $arResult["PATH_TO_ORDERS"],
			"PATH_TO_DETAIL" => $arResult["PATH_TO_ORDER_DETAIL"],
			"SET_TITLE" =>$arParams["SET_TITLE"],
			"ID" => $arResult["VARIABLES"]["ID"],
		),
		$component
	);
	?>
</div>
<div class="clearfix"></div>
<div class="personal_menu">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "",
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => "standard.php"
		),
		false
	);?>
</div>