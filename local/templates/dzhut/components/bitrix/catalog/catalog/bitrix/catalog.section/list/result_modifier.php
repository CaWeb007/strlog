<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption

$maxStrProp = 63;

$arNoViewProp = array('_1','TSENA_ZA_M2','OLDPRICE_SO','OLDPRICE_TO','OLDPRICE_KP','ATT_INSTRUCTIONS','ACTION_1','SAP','ISOVER_ID','VYVOD','BLOG_POST_ID','PROIZVODITEL_1','CML2_BAR_CODE','CML2_ARTICLE','CML2_ATTRIBUTES','CML2_TRAITS','CML2_BASE_UNIT','CML2_TAXES','MORE_PHOTO','FILES','CML2_MANUFACTURER','BONUS','INSTRUKTSIYA_PO_SBORKE','GARANTIYA','BREND','248','BEST','HIT','NEW','MAIN','RAZRESHENNYY_OPLATY_BONUSAMI','_POROGA_NACHISLENIYA_BONUSOV','POD_ZAKAZ_CHEREZ','NALICHIE','PROIZVODITEL','BRAND','MORE_PHOTO','ON_DESCOUNT','ATT_YOUTUBE','BREND_LOGO','BEST_PRICE','vote_count','vote_sum','rating','ATT_RELATIONS_FRONT','ZVEZDA','TIP','NALICHIE','NEKONDITSIYA','NELZYA_OPLACHIVAT_BONUSAMI','NE_NACHISLYAT_BONUSY','FLAG');
foreach($arResult["ITEMS"] as $key => $arElement)
{	
	$strProp = "";
	foreach($arElement['PROPERTIES'] as $valProp){
		if(!in_array($valProp['CODE'],$arNoViewProp)){
			if(!empty($valProp['VALUE'])){
				$strProp .= $valProp['NAME'] .": ". $valProp['VALUE'] . ", ";
			}
				
		}
	}
	
	$arResult["ITEMS"][$key]['MINI_DESC'] = rtrim(substr($strProp,0,$maxStrProp)," ,");
	if(!empty($arResult["ITEMS"][$key]['MINI_DESC'])){
		$arResult["ITEMS"][$key]['MINI_DESC'] .= "...";	
	}
	


	unset($strProp);

	
	$arRes = array();
	foreach($arParams["PROPERTY_CODE"] as $pid)
	{
		$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
	}
	$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"] = $arRes;

	(int)$price = round($arResult["ITEMS"][$key]['PRICES']['BASE']['VALUE']);
	//$old_pr = ($price*100)/(100 - $arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE']);
	if(!empty($arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE']) || $arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE'] != ''){
		$old_pr = IntVal($price) + (IntVal($price)*(IntVal($arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE'])/IntVal(100)));
		$arResult["ITEMS"][$key]['OLD_PRICE'] = round($old_pr);
	}

}

/* Смотрим наличие товара */
foreach($arResult["ITEMS"] as $key => $arElement)
{
		
	$arRes = array();
	foreach($arParams["PROPERTY_CODE"] as $pid)
	{
		$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
	}
	$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"] = $arRes;

	$ELEMENT_ID = $arElement['ID'];
	$PodZakaz = CIBlockElement::GetList(array(),array("ID"=>$ELEMENT_ID),false,false,array("PROPERTY_POD_ZAKAZ_CHEREZ","PROPERTY_NALICHIE"));
	$PodZakazResult = $PodZakaz->GetNext();
	$MutchDay  = $PodZakazResult['PROPERTY_POD_ZAKAZ_CHEREZ_VALUE'];
	$Nalichie = $PodZakazResult['PROPERTY_NALICHIE_VALUE'];	
	
	if(CModule::IncludeModule("catalog")){
	
		$q = CCatalogStoreProduct::GetList(array(),array("PRODUCT_ID"=>$arElement['ID'], 'STORE_ID' => 51),false,false,array("AMOUNT"));

		$res = $q->GetNext();

		// если на 34 складе есть хоть какоето значение
		if (count($res) > 0) {

				if($res['AMOUNT'] != 0 || !empty($Nalichie)){
					$arResult['ITEMS'][$key]['CUSTOM_AMOUNT'] = "Y";
				}
			
		} else { // если на 34 складе поле Количество пустое

			if(!empty($Nalichie)){
				$arResult['ITEMS'][$key]['CUSTOM_AMOUNT'] = "Y";
			}

		}

	}	
	
}

// edebug($arResult["ITEMS"]); 

// описание категории
$DescCategory = CIBlockSection::GetByID($arResult["ITEMS"][0]['IBLOCK_SECTION_ID']);
	$DescCategoryResult = $DescCategory->Fetch();
		//edebug($DescCategoryResult);
if(!empty($DescCategoryResult['DESCRIPTION'])){
$arResult["ITEMS"][0]['CATEGORY_DESCRIPTION'] = $DescCategoryResult['DESCRIPTION'];	
}


?>  