<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор");
?>

<? echo 'GooD!';?>

<?
$APPLICATION->IncludeComponent(
	"sibhronik:calculator", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"TEMPLATE_FOR_DATE" => "m-d-Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"TEPLIT_MOUNTING" => "0",
		"TEPLIT_MOUNTING_PRICE" => "0"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>