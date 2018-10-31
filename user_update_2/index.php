<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("User_upadte");
?>

<?php
$APPLICATION->IncludeComponent(
    "sibhronik:php_uploader",
    "",
    Array(

    )
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>