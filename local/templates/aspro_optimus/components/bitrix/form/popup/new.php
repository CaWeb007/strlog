<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if(isset($_GET['form_id']) && $_GET['form_id'] == "fast_view"):?>
<?$APPLICATION->IncludeComponent("bitrix:form.result.new", "popup_fast_view", $arParams, $component);?>
<?else:?>
<?$APPLICATION->IncludeComponent("bitrix:form.result.new", "popup", $arParams, $component);?>
<?endif?>