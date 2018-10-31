<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Upload json");
?>

<?php 
$APPLICATION->IncludeComponent(
	"sibhronik:uploader", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>