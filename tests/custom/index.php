<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("custom");
//$path = \Bitrix\Main\Application::getDocumentRoot().'/tests/custom/';
$path = '/tests/custom/';
$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addJs($path.'script.js');
//$asset->addJs('/local/templates/aspro_optimus/js/jquery-ui.min.js');
$asset->addJs('/tests/custom/position.min.js');

$asset->addCss($path.'style.css');
?>
<?/*$APPLICATION->IncludeComponent(
	"caweb:custom.elements",
	"",
	array(),
	false
);*/?>
<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"COMPONENT_TEMPLATE" => ".default",
	"PATH" => SITE_DIR."include/mainpage/ecosystem.php",
	"AREA_FILE_SHOW" => "file",
	"AREA_FILE_SUFFIX" => "",
	"AREA_FILE_RECURSIVE" => "Y",
	"EDIT_TEMPLATE" => "standard.php"
),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>