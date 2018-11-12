<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption

foreach($arResult["ITEMS"] as $key => $arElement){
	$arRes = array();
	foreach($arParams["PROPERTY_CODE"] as $pid)
	{
		$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
	}
	$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"] = $arRes;
	
$SectionName = CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);
$resSection = 	$SectionName->GetNext();

$CountElem  = CIBlockSection::GetSectionElementsCount($arElement['IBLOCK_SECTION_ID'],array("CNT_ACTIVE"=>"Y"));
$arResult["ITEMS"][$key]['SECTION_NAME'] = $resSection['NAME'];

if($CountElem == 1){
	$arResult["ITEMS"][$key]['SINGLE'] = 'yes-border';
}
// вычисляем старую цену
	(int)$price = round($arResult["ITEMS"][$key]['PRICES']['BASE']['VALUE']);
	//$old_pr = ($price*100)/(100 - $arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE']);
	$old_pr = ($price/100*35)+$price;
	$arResult["ITEMS"][$key]['OLD_PRICE'] = round($old_pr);
	//echo $price . "<br>";
	//(int)$percent = $arResult["ITEMS"][$key]['PROPERTIES']['_1']['VALUE'] * $price / 100;
	//echo $percent . "<br>";
	//$arResult["ITEMS"][$key]['OLD_PRICE'] = round($price + $percent);
	// edebug($arResult["ITEMS"]);
	 // break;
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

		// если на 51 складе есть хоть какоето значение
		if (count($res) > 0) {

				if($res['AMOUNT'] != 0 || !empty($Nalichie)){
					$arResult['ITEMS'][$key]['CUSTOM_AMOUNT'] = "Y";
				}
			
		} else { // если на 51 складе поле Количество пустое

			if(!empty($Nalichie)){
				$arResult['ITEMS'][$key]['CUSTOM_AMOUNT'] = "Y";
			}

		}

	}	
	
}


// описание категории
$DescCategory = CIBlockSection::GetByID($arResult["ITEMS"][0]['IBLOCK_SECTION_ID']);
	$DescCategoryResult = $DescCategory->Fetch();
		//edebug($DescCategoryResult);
if(!empty($DescCategoryResult['DESCRIPTION'])){
$arResult["ITEMS"][0]['CATEGORY_DESCRIPTION'] = $DescCategoryResult['DESCRIPTION'];	
}





?>  