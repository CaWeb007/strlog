<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if($arItem["CODE"]=="IN_STOCK"){
		sort($arResult["ITEMS"][$key]["VALUES"]);
		if($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
	}
}
$filterPrices = array();
foreach ($arResult['ITEMS'] as $key => $value){
    if ($value['PRICE']) $filterPrices[$key] = $value;
}
if (count($filterPrices) > 1){
    $sort = 0;
    $sortPrices = array();
    foreach ($arResult['PRICES'] as $key => $value){
        if (!$value['CAN_BUY']) continue;
        $sortPrices[$value['SORT']] = $key;
        if ($value['SORT'] > $sort){
            $sort = $value['SORT'];
        }
    }
    unset($filterPrices[$sortPrices[$sort]]);
    $arResult['ITEMS'] = array_diff_key($arResult['ITEMS'], $filterPrices);
}