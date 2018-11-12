<?



//====TOTAL BONUS VAR
$TotalBonus = 0;
/* 
foreach($arResult['GRID']['ROWS'] as $paramVal){	
	foreach($paramVal['data']['PROPS'] as $Bonus){
		if($Bonus['CODE'] == 'BONUS'){
			$TotalBonus += 	$Bonus['VALUE'];
		}
	}
}

$arResult['GRID']['ROWS']['TOTAL_BONUS'] = $TotalBonus; */

/* Смотрим наличие товара */
$arResult["CUSTOM_AMOUNT"] = "Y";
foreach($arResult["BASKET_ITEMS"] as $key => $basket_item)
{
	$ELEMENT_ID = $basket_item['PRODUCT_ID'];
	$PodZakaz = CIBlockElement::GetList(array(),array("ID"=>$ELEMENT_ID),false,false,array("PROPERTY_POD_ZAKAZ_CHEREZ","PROPERTY_NALICHIE"));
	$PodZakazResult = $PodZakaz->GetNext();
	$MutchDay  = $PodZakazResult['PROPERTY_POD_ZAKAZ_CHEREZ_VALUE'];
	$Nalichie = $PodZakazResult['PROPERTY_NALICHIE_VALUE'];
	
	if(CModule::IncludeModule("catalog")){	
			$rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' =>$basket_item["PRODUCT_ID"], 'STORE_ID' => 34), false, false, array('AMOUNT')); 
			if ($arStore = $rsStore->Fetch()){
				if ($arStore["AMOUNT"] == 0 && empty($Nalichie)) {
					$arResult["CUSTOM_AMOUNT"] = "N";
					break;
				}
			}
	}

}


/*
 свойства пользователя не подставляются в arResult, поэтому получим их сами
*/
if ($USER->IsAuthorized()) {
	$id = $USER->getID();
	$rsUser = CUser::GetByID($id);
	$arUser = $rsUser->Fetch();
	
	$arResult["MY_USER_DATA"] = $arPropVals;
	foreach ($arResult["ORDER_PROP"] as $key => $value) {
		foreach ($value as $prop_key => $prop) {
			switch ($prop["CODE"]) {
				case 'FIO':
					$arResult["ORDER_PROP"][$key][$prop_key]["VALUE"] = $arUser["NAME"];					
					break;
				case 'PHONE':
					$arResult["ORDER_PROP"][$key][$prop_key]["VALUE"] = $arUser["PERSONAL_PHONE"];					
					break;
				case 'LAST_NAME':
					$arResult["ORDER_PROP"][$key][$prop_key]["VALUE"] = $arUser["LAST_NAME"];					
					break;
				case 'SECOND_NAME':
					$arResult["ORDER_PROP"][$key][$prop_key]["VALUE"] = $arUser["SECOND_NAME"];					
					break;
				case 'EMAIL':
					$arResult["ORDER_PROP"][$key][$prop_key]["VALUE"] = $arUser["EMAIL"];					
					break;
				default:
					break;
			}
		}
	}

}

$phone = false;
$confirm_phone = "N";
foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $key => $value) {
	if ($value["CODE"] == "PHONE") {
		$phone = $key;
	}
}

if ($phone !== false) {
	$arResult["ORDER_PROP"]["USER_PROPS_Y"][$phone]["CONFIRM_PHONE"] = ($arUser["UF_CONFIRM_PHONE"]) ? "Y" : "N";
}