<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("custom");
?>
<?$APPLICATION->IncludeComponent(
	"caweb:custom.elements",
	"",
	array(),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>