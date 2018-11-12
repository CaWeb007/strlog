<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arDefaultParams = array(
	'TEMPLATE_THEME' => 'blue',
);
$arParams = array_merge($arDefaultParams, $arParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
		$arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';

if ($arResult["ELEMENT"]['DETAIL_PICTURE'] || $arResult["ELEMENT"]['PREVIEW_PICTURE'])
{
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["ELEMENT"]['DETAIL_PICTURE'] ? $arResult["ELEMENT"]['DETAIL_PICTURE'] : $arResult["ELEMENT"]['PREVIEW_PICTURE'],
		array("width" => "150", "height" => "180"),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);
	$arResult["ELEMENT"]['DETAIL_PICTURE'] = $arFileTmp;
}

$SUM_DR = 0;
$SUM_DS = 0;
$d = getCartDiscountByIDElement($arResult["ELEMENT"]["ID"],(float)$arResult["ELEMENT"][ "CATALOG_PRICE_1"]);
if(0 < count($d) && $d['isOne'] && $d['discount']){
	$SUM_DR = $arResult["ELEMENT"][ "CATALOG_PRICE_1"] - $d['discount'];
	$SUM_DS = $d['discount'];
}

$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);

$SUM_DO = (float)$arResult["ELEMENT"][ "CATALOG_PRICE_1"];

foreach ($arResult["SET_ITEMS"][ "DEFAULT"] as $key=>$arItem)
{
	$d = [];
	$d = getCartDiscountByIDElement($arItem['ID'],$arItem['PRICE_VALUE']);
	if(0 < count($d) && $d['isOne'] && $d['discount']){
		//$arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_DISCOUNT_VALUE'] =  $arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_VALUE'] - $d['discount'];
		//$arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_PRINT_DISCOUNT_VALUE'] =  CCurrencyLang::CurrencyFormat($arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_DISCOUNT_VALUE'], 'RUB');
		$SUM_DR += $arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_VALUE'] - $d['discount'];
		$SUM_DS += $d['discount'];
	}
	$SUM_DO += $arItem['PRICE_VALUE'];
	//$SUM_DR += $arResult["SET_ITEMS"][ "DEFAULT"][$key]['PRICE_DISCOUNT_VALUE'];
	//$SUM_DS += $d['discount'];
}

foreach ($arResult["SET_ITEMS"][ "OTHER"] as $key=>$arItem)
{
	$d = [];
	$d = getCartDiscountByIDElement($arItem['ID'],$arItem['PRICE_VALUE']);
	if(0 < count($d) && $d['isOne'] && $d['discount']){
		//$arResult["SET_ITEMS"][ "OTHER"][$key]['PRICE_DISCOUNT_VALUE'] =  $arResult["SET_ITEMS"][ "OTHER"][$key]['PRICE_VALUE'] - $d['discount'];
		//$arResult["SET_ITEMS"][ "OTHER"][$key]['PRICE_PRINT_DISCOUNT_VALUE'] =  CCurrencyLang::CurrencyFormat($arResult["SET_ITEMS"][ "OTHER"][$key]['PRICE_DISCOUNT_VALUE'], 'RUB');
		$SUM_DR += $arResult["SET_ITEMS"][ "OTHER"][$key]['PRICE_VALUE'] - $d['discount'];
		$SUM_DS += $d['discount'];
	}

	$arResult["SET_ITEMS"]["DEFAULT"][] = $arResult["SET_ITEMS"][ "OTHER"][$key];
	$SUM_DO += $arItem['PRICE_VALUE'];
}

$arResult["SET_ITEMS"][ "OTHER"] = [];
$arResult["SET_ITEMS"][ "OLD_PRICE"] = CCurrencyLang::CurrencyFormat($SUM_DO, 'RUB');
$arResult["SET_ITEMS"][ "PRICE"] = CCurrencyLang::CurrencyFormat($SUM_DR, 'RUB');
$arResult["SET_ITEMS"][ "PRICE_DISCOUNT_DIFFERENCE"] = CCurrencyLang::CurrencyFormat($SUM_DS, 'RUB');

if(0 < $arResult["SET_ITEMS"][ "PRICE_DISCOUNT_DIFFERENCE"]){
	$arResult['SHOW_DEFAULT_SET_DISCOUNT'] = true;
}
	//if(isset($_GET['aertc'])) var_dump("<pre>",$arResult);
foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		$arElement = array(
			"ID"=>$arItem["ID"],
			"NAME" =>$arItem["NAME"],
			"DETAIL_PAGE_URL"=>$arItem["DETAIL_PAGE_URL"],
			"DETAIL_PICTURE"=>$arItem["DETAIL_PICTURE"],
			"PREVIEW_PICTURE"=> $arItem["PREVIEW_PICTURE"],
			"PRICE_CURRENCY" => $arItem["PRICE_CURRENCY"],
			"PRICE_DISCOUNT_VALUE" => $arItem["PRICE_DISCOUNT_VALUE"],
			"PRICE_PRINT_DISCOUNT_VALUE" => $arItem["PRICE_PRINT_DISCOUNT_VALUE"],
			"PRICE_VALUE" => $arItem["PRICE_VALUE"],
			"PRICE_PRINT_VALUE" => $arItem["PRICE_PRINT_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE_VALUE" => $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE" => $arItem["PRICE_DISCOUNT_DIFFERENCE"],
			"CAN_BUY" => $arItem['CAN_BUY'],
			"SET_QUANTITY" => $arItem['SET_QUANTITY'],
			"MEASURE_RATIO" => $arItem['MEASURE_RATIO'],
			"BASKET_QUANTITY" => $arItem['BASKET_QUANTITY'],
			"MEASURE" => $arItem['MEASURE']
		);
		if ($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_VALUE"];
		if ($arItem["PRICE_CONVERT_VALUE"])
			$arElement["PRICE_CONVERT_VALUE"] = $arItem["PRICE_CONVERT_VALUE"];
		if ($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"];

		if ($type == "DEFAULT")
			$arDefaultSetIDs[] = $arItem["ID"];
		if ($arItem['DETAIL_PICTURE'] || $arItem['PREVIEW_PICTURE'])
		{
			$arFileTmp = CFile::ResizeImageGet(
				$arItem['DETAIL_PICTURE'] ? $arItem['DETAIL_PICTURE'] : $arItem['PREVIEW_PICTURE'],
				array("width" => "150", "height" => "180"),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arElement['DETAIL_PICTURE'] = $arFileTmp;
		}

		$arResult["SET_ITEMS"][$type][$key] = $arElement;
	}
}
$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;