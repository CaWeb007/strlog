<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */
$arResult["BONUSES_DATA"] = array();
$arFilter = Array(Array("ID"=>$USER->GetID()));
$userDataList = Bitrix\Main\UserTable::getList(Array(
    "select"=>Array("UF_BONUSES"),
    "filter"=>$arFilter,
));
while ($userData = $userDataList->fetch()) {
    $userBonuses = $userData["UF_BONUSES"];
}
$arResult["JS_DATA"]["BONUSES"] = $userBonuses;


$totalBonuses = 0;
$totalAccrual = 0;
$USE_BONUSES_SUMM = 0;
foreach($arResult["JS_DATA"]["GRID"]["ROWS"] as $key => $value) {
    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["BONUSES"] = $arResult["JS_DATA"]["BONUSES"];//Количество бонусов пользователя
    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["BONUSES_DISCOUNT"] = $value["data"]["PROPERTY_RAZRESHENNYY_OPLATY_BONUSAMI_VALUE"];//Процент который можно оплатить бонусами
    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["TOTAL_BONUSES_DISCOUNT"] = round($value["data"]["SUM_NUM"] * ($arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["BONUSES_DISCOUNT"] / IntVal(100)));
    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["BONUSES_ACCRUAL"] = $value["data"]["PROPERTY__POROGA_NACHISLENIYA_BONUSOV_VALUE"];
    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["TOTAL_BONUSES_ACCRUAL"] = round($value["data"]["SUM_NUM"] * ($arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["BONUSES_ACCRUAL"] / IntVal(100)));
    $totalBonuses += $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["TOTAL_BONUSES_DISCOUNT"];//Сумма которую можно оплатить бонусами
    $totalAccrual += $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["BONUSES_DATA"]["TOTAL_BONUSES_ACCRUAL"];//Количество начисляемых бонусов
	
	/* */
	$USE_BONUSES_SUMM += $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["SUM_NUM"];
}
$arResult["JS_DATA"]["TOTAL_BONUS_SUM"] = $totalBonuses;
$arResult["JS_DATA"]["TOTAL_ACCRUAL_SUM"] = $totalAccrual;
$arResult["JS_DATA"]["TEMPLATE_FOLDER"] = $this->GetFolder();

$arResult["JS_DATA"]["USE_BONUSES_SUMM"] = $USE_BONUSES_SUMM;
//unset($arResult["PERSON_TYPE"][1]);

//echo '<pre>';
//var_dump($arResult);
//echo '</pre>';

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);