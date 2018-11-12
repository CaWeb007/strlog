<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// edebug($arResult);

if (empty($arResult))
    return;
 
 
if(CModule::IncludeModule("catalog")){
    
 
}

$arParams["MENU_THEME"] = "blue";


$arSectionsInfo = array();


$arMenuItemsIDs = array();
$arAllItems = array();
foreach($arResult as $key=>$arItem)
{
	if($arItem["DEPTH_LEVEL"] > $arParams["MAX_LEVEL"])
	{
		unset($arResult[$key]);
		continue;
	}

	$arItem["PARAMS"]["item_id"] = crc32($arItem["LINK"]);


	if ($arItem["DEPTH_LEVEL"] == "1")
	{
		$arMenuItemsIDs[$arItem["PARAMS"]["item_id"]] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_1 = $arItem["PARAMS"]["item_id"];
		}
		$arAllItems[$arItem["PARAMS"]["item_id"]] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "2")
	{
		$arMenuItemsIDs[$curItemLevel_1][$arItem["PARAMS"]["item_id"]] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_2 = $arItem["PARAMS"]["item_id"];
		}
		$arAllItems[$arItem["PARAMS"]["item_id"]] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "3")
	{
		$arMenuItemsIDs[$curItemLevel_1][$curItemLevel_2][] = $arItem["PARAMS"]["item_id"];
		$arAllItems[$arItem["PARAMS"]["item_id"]] = $arItem;
	}
}



$arMenuStructure = array();
foreach ($arMenuItemsIDs as $itemIdLevel_1=>$arLevels2)
{
	$countItemsInRow = 18;
	$arMenuStructure[$itemIdLevel_1] = array();
	$countLevels2 = count($arLevels2);

	if ($countLevels2 > 0)
	{
		for ($i=1; $i<=3; $i++)
		{
			$sumElementsInRow = 0;
			foreach($arLevels2 as $itemIdLevel_2=>$arLevels3)
			{
				$sumElementsInRow+= count($arLevels3) + 1;
				$arMenuStructure[$itemIdLevel_1][$i][$itemIdLevel_2] = $arLevels3;
				if ($sumElementsInRow > $countItemsInRow)
					$countItemsInRow = $sumElementsInRow;

				unset($arLevels2[$itemIdLevel_2]);
				$tmpCount = 0;
				foreach($arLevels2 as $tmpItemIdLevel_2=>$arTmpLevels3)
				{
					$tmpCount+= 1 + count($arTmpLevels3);
				}

				if ($tmpCount <= $countItemsInRow*(3-$i) && $countItemsInRow<=$sumElementsInRow)
					break;
			}
		}
	}
}

$arResult = array();
$arResult["ALL_ITEMS"] = $arAllItems;
$arResult["ALL_ITEMS_ID"] = $arMenuItemsIDs;
$arResult["MENU_STRUCTURE"] = $arMenuStructure;
/*
// DELATE EMPTY SECTION

foreach($arResult["ALL_ITEMS"] as $key => $link){
	
	 $arFilter = Array('IBLOCK_ID'=>24,'NAME'=>$link['TEXT'], 'GLOBAL_ACTIVE'=>'Y','ACTIVE'=>'Y','CNT_ACTIVE'=>'Y');
	  $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
	  while($ar_result = $db_list->GetNext())
	  {
		 
			 if(intval($ar_result['ELEMENT_CNT']) === 0){
				unset($arResult["ALL_ITEMS"][$key]);
				unset($arResult["MENU_STRUCTURE"][$key]);
				unset($arResult["ALL_ITEMS_ID"][$key]);
			 }

	  }

}
//edebug($arResult["ALL_ITEMS"]);*/
?>

