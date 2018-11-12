<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption

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
	
		$q = CCatalogStoreProduct::GetList(array(),array("PRODUCT_ID"=>$arElement['ID'], 'STORE_ID' => 34),false,false,array("AMOUNT"));

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
//edebug($arResult["ITEMS"]); IBLOCK_SECTION_ID

// описание категории
$DescCategory = CIBlockSection::GetByID($arResult["ITEMS"][0]['IBLOCK_SECTION_ID']);
	$DescCategoryResult = $DescCategory->Fetch();
		//edebug($DescCategoryResult);
if(!empty($DescCategoryResult['DESCRIPTION'])){
$arResult["ITEMS"][0]['CATEGORY_DESCRIPTION'] = $DescCategoryResult['DESCRIPTION'];	
}



?>  