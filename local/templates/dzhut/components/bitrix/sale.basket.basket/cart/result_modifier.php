<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;

$defaultParams = array(
	'TEMPLATE_THEME' => 'blue'
);
$arParams = array_merge($defaultParams, $arParams);
unset($defaultParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = (string)Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? 'eshop_adapt' : $templateId;
		$arParams['TEMPLATE_THEME'] = (string)Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';



//====TOTAL BONUS VAR
$TotalBonus = 0;
$arTovarBonus = array();

foreach($arResult['GRID']['ROWS'] as $key => $paramVal){	
	$arTovarBonus[] = $paramVal['PRODUCT_ID'];
}

/* Необходимо учесть вариант заказа товаров из инфоблока Некондиция, поэтому получим IBLOCK_ID для каждого товара в корзине */
if(CModule::IncludeModule("iblock")) {

	$arResult['GRID']['ROWS']['ELEMENT'] = array();	

	foreach ($arResult["GRID"]["ROWS"] as $key => $value) {

		$arSelect = Array("ID", "IBLOCK_ID");
		$arFilter = Array("ID" => $value["PRODUCT_ID"]);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement())
		{
		 	$iblock_id[$key] = $ob->GetFields();
		 	$arResult["GRID"]["ROWS"][$key]["IBLOCK_ID"] = $iblock_id[$key]["IBLOCK_ID"];
		}

		/* получим бонус для данного товара */

		$arFilter = array(
			"IBLOCK_ID" => $arResult["GRID"]["ROWS"][$key]["IBLOCK_ID"],
			"ACTIVE" => "Y",
			"ID" => $value["PRODUCT_ID"]
		);

		$Prop = CIBlockElement::GetList(array(),$arFilter,false,false,array("ID","PROPERTY_BONUS"));

		while($PropRes = $Prop->GetNext()){

			$arResult['GRID']['ROWS']['ELEMENT'][$PropRes['ID']] = $PropRes['PROPERTY_BONUS_VALUE'];
			
			$arResult['GRID']['ROWS']['TOTAL_BONUS'] += $PropRes['PROPERTY_BONUS_VALUE'];
		}

	}

}


/*if(CModule::IncludeModule("iblock")){

	$arFilter = array(
		"IBLOCK_ID" => 24,
		"ACTIVE" => "Y",
		"ID" => $arTovarBonus
	);

$Prop = CIBlockElement::GetList(array(),$arFilter,false,false,array("ID","PROPERTY_BONUS"));

$arResult['GRID']['ROWS']['ELEMENT'] = array();	

while($PropRes = $Prop->GetNext()){

	$arResult['GRID']['ROWS']['ELEMENT'][$PropRes['ID']] = $PropRes['PROPERTY_BONUS_VALUE'];
	
	$arResult['GRID']['ROWS']['TOTAL_BONUS'] += $PropRes['PROPERTY_BONUS_VALUE'];
}
	
}*/
//edebug($arResult['GRID']['ROWS']);
//==== // TOTAL BONUS VAR

//edebug($arResult['GRID']['ROWS']);

//$arResult['GRID']['ROWS']['TOTAL_BONUS'] = $TotalBonus;