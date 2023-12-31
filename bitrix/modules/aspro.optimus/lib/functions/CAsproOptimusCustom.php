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
				$html = '<div class="alert alert-warning">СНЯТ С ПРОДАЖИ</div>';
				$arOptions['HTML'] = $html;
			}
		}

		function OnAsproSkuShowItemPricesHandler($arParams, $arItem, &$item_id, &$min_price_id, $arItemIDs, $bShort, &$html){
			//... some code'
		}
	}
}?>