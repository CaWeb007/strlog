<?
namespace Aspro\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Web\DOM\Document;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\DOM\CssParser;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);
\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');

//user custom functions

if(!class_exists("CAsproOptimusCustom"))
{
	class CAsproOptimusCustom{
		const MODULE_ID = \COptimus::moduleID;

		function OnAsproItemShowItemPricesHandler($arParams, $arPrices, $strMeasure, &$price_id, $bShort, &$html){
			// ... some code
		}

		function OnAsproGetTotalQuantityBlockHandler($totalCount, &$arOptions){
			//... some code
			if($totalCount === -989){
				$arOptions['HTML'] = "";
			}
			//	$arOptions["OPTIONS"]['USE_WORD_EXPRESSION'] = "Y";
		}

	 	function OnAsproGetTotalQuantityHandler($arItem, &$totalCount){
			//... some code
			if(isset($arItem['PROPERTIES']['NOT_WORK']) && $arItem['PROPERTIES']['NOT_WORK']["VALUE_ENUM"]=="ДА"){
				$totalCount = -989;
			}
		}
		function OnAsproGetBuyBlockElementHandler($arItem, $totalCount, $arParams, &$arOptions){
			//... some code
			if(isset($arItem['PROPERTIES']['NOT_WORK']) && $arItem['PROPERTIES']['NOT_WORK']["VALUE_ENUM"]=="ДА"){
				$html = '<div class="alert alert-warning">Снят с производства</div>';
				$arOptions['HTML'] = $html;
			}
		}

		function OnAsproSkuShowItemPricesHandler($arParams, $arItem, &$item_id, &$min_price_id, $arItemIDs, $bShort, &$html){
			//... some code'
		#	if(count($arItem['OFFERS']) > 0){//$arParams["DISPLAY_TYPE"]=="table"){
				#varDump($html,$min_price_id);
				$PRICE =  $arItrm['PRICES'][$min_price_id]["PRINT_VALUE"];
				#$html = preg_replace("#<span class\=\"values_wrapper\">(+)</span>#
				$measure_block = '';
			
				if($arParams["SHOW_MEASURE"]=="Y")
				{
					$minPrice = $arItem['MIN_PRICE'];
					$measure_block = "<span class=\"price_measure\">/";
					$measure_block .= $minPrice["CATALOG_MEASURE_NAME"];
					$measure_block .= "</span>";
					
					$arPrice = current($arItem['PRICES']);
					$price_field = "VALUE";
					$val = '';
					$format_value = \CCurrencyLang::CurrencyFormat($arPrice[$price_field], $arPrice['CURRENCY'], false);
				
					$val = str_replace($format_value, '<span class="price_value">'.$format_value.'</span><span class="price_currency">', $arPrice["PRINT_".$price_field].'</span>');
				
					$val = '<span class="values_wrapper">'.$val.'</span>';
				
					$html = preg_replace("#((от)?\s?<span\sclass=\"values_wrapper\"[^>]*>[^<]+</span>)#su",$val.$measure_block,$html, 1);
				}
			
		#	}
		}
	}
}?>