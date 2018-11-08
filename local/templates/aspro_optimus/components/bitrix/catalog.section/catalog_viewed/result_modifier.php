<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
$arDefaultParams = array(
	'TYPE_SKU' => 'N',
	'OFFER_TREE_PROPS' => array('-'),
);
$arParams = array_merge($arDefaultParams, $arParams);

if ('TYPE_2' != $arParams['TYPE_SKU'] )
	$arParams['TYPE_SKU'] = 'N';

if ('TYPE_2' == $arParams['TYPE_SKU'] && $arParams['DISPLAY_TYPE'] !='table' ){
	if (!is_array($arParams['OFFER_TREE_PROPS']))
		$arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
	foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
	{
		$value = (string)$value;
		if ('' == $value || '-' == $value)
			unset($arParams['OFFER_TREE_PROPS'][$key]);
	}
	if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES']))
	{
		$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
		foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
		{
			$value = (string)$value;
			if ('' == $value || '-' == $value)
				unset($arParams['OFFER_TREE_PROPS'][$key]);
		}
	}
}else{
	$arParams['OFFER_TREE_PROPS'] = array();
}


if (!empty($arResult['ITEMS'])){
	$arEmptyPreview = false;
	$strEmptyPreview = $this->GetFolder().'/images/no_photo.png';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
	{
		$arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
		if (!empty($arSizes))
		{
			$arEmptyPreview = array(
				'SRC' => $strEmptyPreview,
				'WIDTH' => intval($arSizes[0]),
				'HEIGHT' => intval($arSizes[1])
			);
		}
		unset($arSizes);
	}
	unset($strEmptyPreview);

	$arSKUPropList = array();
	$arSKUPropIDs = array();
	$arSKUPropKeys = array();
	$boolSKU = false;
	$strBaseCurrency = '';
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

	if ($arResult['MODULES']['catalog'])
	{
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();

	}
	
	$arNewItemsList = array();
	foreach ($arResult['ITEMS'] as $key => $arItem)
	{
		if($key>7)
			continue;
		$arItem['CHECK_QUANTITY'] = false;
		if (!isset($arItem['CATALOG_MEASURE_RATIO']))
			$arItem['CATALOG_MEASURE_RATIO'] = 1;
		if (!isset($arItem['CATALOG_QUANTITY']))
			$arItem['CATALOG_QUANTITY'] = 0;
		$arItem['CATALOG_QUANTITY'] = (
			0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
			? floatval($arItem['CATALOG_QUANTITY'])
			: intval($arItem['CATALOG_QUANTITY'])
		);
		$arItem['CATALOG'] = false;
		if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
			$arItem['CATALOG_SUBSCRIPTION'] = 'N';

		if ($arResult['MODULES']['catalog'])
		{
			$arItem['CATALOG'] = true;
			if (!isset($arItem['CATALOG_TYPE']))
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
			if (
				(CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
				&& !empty($arItem['OFFERS'])
			)
			{
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
			}
			switch ($arItem['CATALOG_TYPE'])
			{
				case CCatalogProduct::TYPE_SET:
					$arItem['OFFERS'] = array();
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
				case CCatalogProduct::TYPE_SKU:
					break;
				case CCatalogProduct::TYPE_PRODUCT:
				default:
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
			}
		}
		else
		{
			$arItem['CATALOG_TYPE'] = 0;
			$arItem['OFFERS'] = array();
		}

		if(isset($arItem["PROPERTIES"]) && $arItem["PROPERTIES"]){
			unset($arItem["PROPERTIES"]);
		}


		if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		{
			$arItem['MIN_PRICE'] = COptimus::getMinPriceFromOffersExt(
				$arItem['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
			);
		}

		if (
			$arResult['MODULES']['catalog']
			&& $arItem['CATALOG']
			&&
				($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
				|| $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
		)
		{
			CIBlockPriceTools::setRatioMinPrice($arItem, false);
			$arItem['MIN_BASIS_PRICE'] = $arItem['MIN_PRICE'];
		}

		if (!empty($arItem['DISPLAY_PROPERTIES']))
		{
			foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
			{
				if ('F' == $arDispProp['PROPERTY_TYPE'])
					unset($arItem['DISPLAY_PROPERTIES'][$propKey]);				

			}
		}
		
		/* PRICE 'TO'=>'С' */
		$isPriceC = false;
		if(isset($arItem["PROPERTIES"]["CML2_TRAITS"])){
			foreach($arItem["PROPERTIES"]["CML2_TRAITS"]["DESCRIPTION"] as $dk=>$dv){
				if($dv == "Ценовая группа"){
					$isPriceC = isIssetPG($arItem["PROPERTIES"]["CML2_TRAITS"]["VALUE"][$dk]);break;
				}
			}
		}
		
		if($isPriceC) {
			if(isset($arItem['PRICES']['ТО'])){
				unset($arItem['PRICES']['ТО']);
			}
		} else {
			if(isset($arItem['PRICES']['С'])){
				unset($arItem['PRICES']['С']);
			}
		}
		
		/* end PRICE 'TO' */
		
		/* OLD PRICE */
		if(isset($arItem["PROPERTIES"]["OLDPRICE_TO"]) || isset($arItem["PROPERTIES"]["OLDPRICE_SO"]) || isset($arItem["PROPERTIES"]["OLDPRICE_KP"])){
			$arPRICES_ID = ['ТО' => 'TO','СО' => 'SO','КП' => 'KP'];
			if((float)$arItem["PROPERTIES"]["OLDPRICE_TO"] > 0 || (float)$arItem["PROPERTIES"]["OLDPRICE_SO"] > 0 || (float)$arItem["PROPERTIES"]["OLDPRICE_KP"] > 0) {
				foreach($arItem['PRICES'] as $PKEY => $PRICE){
					if(isset($arPRICES_ID[$PKEY]) && (float)$arItem["PROPERTIES"]["OLDPRICE_" . $arPRICES_ID[$PKEY]]['VALUE'] > 0) {
						$oldPrice = (float)$arItem["PROPERTIES"]["OLDPRICE_" . $arPRICES_ID[$PKEY]]['VALUE'];
						if((float)$PRICE['DISCOUNT_VALUE'] > 0 && (float)$PRICE['DISCOUNT_VALUE'] < $oldPrice){
							$arItem['PRICES'][$PKEY]['VALUE'] = $oldPrice;
							$arItem['PRICES'][$PKEY]['PRINT_VALUE'] = $oldPrice . " руб";
						}elseif((float)$PRICE['VALUE'] > 0 && (float)$PRICE['VALUE'] < $oldPrice){
							$arItem['PRICES'][$PKEY]['DISCOUNT_VALUE'] = $arItem['PRICES'][$PKEY]['VALUE'];
							$arItem['PRICES'][$PKEY]['PRINT_DISCOUNT_VALUE'] = $arItem['PRICES'][$PKEY]['VALUE'] . " руб";
							$arItem['PRICES'][$PKEY]['VALUE'] = $oldPrice;
							$arItem['PRICES'][$PKEY]['PRINT_VALUE'] = $oldPrice . " руб";
						}
					}
					
				}
			}
		}
		
		$arItem['LAST_ELEMENT'] = 'N';
		$arNewItemsList[$key] = $arItem;
	}
	
	$arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
	$arResult['ITEMS'] = $arNewItemsList;
	$arResult['SKU_PROPS'] = $arSKUPropList;
	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;


	unset($arNewItemsList);
	$arResult['CURRENCIES'] = array();

	if($GLOBALS[$arParams["FILTER_NAME"]]["ID"]){
		$arTmp=array();
		foreach($GLOBALS[$arParams["FILTER_NAME"]]["ID"] as $id){
			foreach($arResult["ITEMS"] as $arItem){
				if($arItem["ID"]==$id){
					$arTmp[]=$arItem;
				}
			}
		}
		$arResult["ITEMS"]=$arTmp;
		unset($arTmp);
	}
	$count=count($arResult["ITEMS"]);
	$diff=8-$count;
	if($count<8){
		for($i=1;$i<=$diff;$i++){
			$arResult["ITEMS"][]='';
		}
	}
}?>