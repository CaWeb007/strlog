<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Date field");
?>

<?php 
$APPLICATION->IncludeComponent(
	"sibhronik:datefield", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CALENDAR_TYPE" => array(
			0 => "15.05.2018",
			1 => "16.05.2018",
			2 => "",
		),
		"CALENDAR_TIME" => "N"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>