<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_SUBSCRIBE_NEW"));?>
<div class="personal_wrapper">
	<?$APPLICATION->IncludeComponent(
		'bitrix:catalog.product.subscribe.list',
		'',
		array('SET_TITLE' => $arParams['SET_TITLE_SUBSCRIBE'])
		,
		$component
	);?>
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


