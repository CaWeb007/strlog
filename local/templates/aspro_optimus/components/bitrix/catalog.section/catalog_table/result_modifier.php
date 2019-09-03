<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Caweb\Main\Catalog\Search;

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
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arResultModules['currency'] = true;
			if($arResult['CURRENCY_ID'])
			{
				$arConvertParams['CURRENCY_ID'] = $arResult['CURRENCY_ID'];
			}
			else
			{
				$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
				if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
				{
					$arParams['CONVERT_CURRENCY'] = 'N';
					$arParams['CURRENCY_ID'] = '';
				}
				else
				{
					$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
					$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				}
			}
		}
	}

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
	if (!$boolConvert)
		$strBaseCurrency = CCurrency::GetBaseCurrency();
	

	if(isset($_REQUEST['groupper'])){
		foreach ($arResult['ITEMS'] as $key => $arItem){
			$arResult['ITEMS'][$key]['groupper']['ID'] = $_REQUEST['groupperID'];
			$arResult['ITEMS'][$key]['groupper']['CODE'] = $_REQUEST['groupper'];
			$arResult['ITEMS'][$key]['groupper']['TITLE'] = $_REQUEST['groupperTitle'];
			$PRCODE = $_REQUEST['groupper'];
			if(isset($arItem["PROPERTIES"][$PRCODE]["VALUE"])){
				$arResult['ITEMS'][$key]['groupper']['VALUE'] = $arItem["PROPERTIES"][$PRCODE]["VALUE"];
			}
		}
	}

	$arNewItemsList = array();
	Search::getInstance()->initSort();
	foreach ($arResult['ITEMS'] as $key => $arItem)
	{
		Search::getInstance()->setElementArray($key, $arItem);
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

		if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		{
			//set min price when USE_PRICE_COUNT
			#varDump($arParams['USE_PRICE_COUNT']);
			if($arParams['USE_PRICE_COUNT'] == 'Y')
			{
				foreach($arItem['OFFERS'] as $keyOffer => $arOffer)
				{
					//format prices when USE_PRICE_COUNT
					if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'])
					{
						$arPriceTypeID = array();
						if($arOffer['PRICES'])
						{
							foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
							{
								if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
								{
									$arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
									$arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
								}
							}
						}
						$arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
						if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
						{
							$arOffer['PRICE_MATRIX'] = '';
						}
					}
					$arItem['OFFERS'][$keyOffer] = array_merge($arOffer, COptimus::formatPriceMatrix($arOffer));
				}
			}

			$arItem['MIN_PRICE'] = COptimus::getMinPriceFromOffersExt(
				$arItem['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency,
				false
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

		//set min price when USE_PRICE_COUNT
		if($arParams['USE_PRICE_COUNT'] == 'Y' && !$arItem['OFFERS'])
		{
			$arItem["FIX_PRICE_MATRIX"] = COptimus::checkPriceRangeExt($arItem);
		}
		
		//format prices when USE_PRICE_COUNT
		$arItem = array_merge($arItem, COptimus::formatPriceMatrix($arItem));

        /*MINI_DESC*/
        $itemProps = "";
        $itemPropsLength = 300;
        $itemSearchProps = array('PLOSHCHAD_M2', 'DLINA_MM_1', 'SHIRINA_MM', 'PLOTNOST_KG_M2_1', 'TOLSHCHINA_MM', 'KOLICHESTVO_V_UPAKOVKE_SHT');
        foreach($arItem['PROPERTIES'] as $itemProp){
            if(in_array($itemProp['CODE'], $itemSearchProps)){
                if(!empty($itemProp['VALUE'])){
					$itemProps .= ($itemProps?', ':"").$itemProp['NAME'].': '.$itemProp['VALUE'];
                }
            }
        }
        $arItem['MINI_DESC'] = rtrim(substr($itemProps, 0, $itemPropsLength));
        /*/MINI_DESC*/
		
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
    $arNewItemsList = Search::getInstance()->sortElements($arNewItemsList);
	$arResult['ITEMS'] = $arNewItemsList;
	$arResult['SKU_PROPS'] = $arSKUPropList;
	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

	$arResult['CURRENCIES'] = array();
	if ($arResult['MODULES']['currency'])
	{
		if ($boolConvert)
		{
			$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
			$arResult['CURRENCIES'] = array(
				array(
					'CURRENCY' => $arResult['CONVERT_CURRENCY']['CURRENCY_ID'],
					'FORMAT' => array(
						'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT' => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS' => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
					)
				)
			);
			unset($currencyFormat);
		}
		else
		{
			$currencyIterator = CurrencyTable::getList(array(
				'select' => array('CURRENCY')
			));
			while ($currency = $currencyIterator->fetch())
			{
				$currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
				$arResult['CURRENCIES'][] = array(
					'CURRENCY' => $currency['CURRENCY'],
					'FORMAT' => array(
						'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT' => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS' => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
					)
				);
			}
			unset($currencyFormat, $currency, $currencyIterator);
		}
	}
}
?>
