<?
$countItems = 0;
$catalog_id=\Bitrix\Main\Config\Option::get("aspro.optimus", "CATALOG_IBLOCK_ID", COptimusCache::$arIBlocks[SITE_ID]['aspro_optimus_catalog']['aspro_optimus_catalog'][0]);
$arSections = COptimusCache::CIBlockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('TAG' => COptimusCache::GetIBlockCacheTag($catalog_id), 'GROUP' => array('ID'))), array('IBLOCK_ID' => $catalog_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y','CNT_ACTIVE' => 'Y' ,'<DEPTH_LEVEL' =>\Bitrix\Main\Config\Option::get("aspro.optimus", "MAX_DEPTH_MENU", 2)), true, array("ID", "NAME", "PICTURE", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID"));
if($arSections){
	$arResult = array();
	$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
	$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);

	foreach($arSections as $ID => $arSection){
	
		//Вывод количества элементов*start
		
		/*$cnt = CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => 16, 'SECTION_ID'=>$ID, 'INCLUDE_SUBSECTIONS'=>'Y', 'CNT_ACTIVE'=>true, "ACTIVE" => 'Y'),
			array(),
			false,
			array('ID', 'NAME')
		);
		$arSections[$ID]["COUNT"] = $cnt;*/
		//Вывод количества элементов*end
		$arSections[$ID]['SELECTED'] = CMenu::IsItemSelected($arSection['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index);
		if($arSection['PICTURE']){
			$img=CFile::ResizeImageGet($arSection['PICTURE'], Array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arSections[$ID]['IMAGES']=$img;
		}
		if($arSection['IBLOCK_SECTION_ID']){
			if(!isset($arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'])){
				$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'] = array();
			}
			$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'][] = &$arSections[$arSection['ID']];
		}

		if($arSection['DEPTH_LEVEL'] == 1){
			$arResult[] = &$arSections[$arSection['ID']];
		}
	}

}
if(count($arResult) > 0){
	$heightWrapMenu = 0;
	foreach($arResult as $key => $arItem){
		$strlen = strlen($arItem["NAME"]);
		$height1Level = 44;

		if ($strlen > 26) 
			$height1Level = 65;
		if ($strlen > 26*2) 
			$height1Level = 85;

		$heightWrapMenu += $height1Level;
	}

	//	printArr($heightWrapMenu - 40);
	$heightWrapMenu = 775;
	$arResult['MAX_COLUM_HEIGHT'] = $heightWrapMenu+10;
	
	foreach($arResult as $key => $arItem){
	
		$columnHeight = $heightWrapMenu - 40;
		$chFirstLevel1 = 50;
		$chFirstLevel2 = 30;
		$chSecondLevel = 22;
		$chSecondLevel2 = 42;
		$chSecondLevel3 = 62;

		if($arItem["CHILD"]){
			foreach($arItem["CHILD"] as $chKey => $arChildItem){

				$strlen = iconv_strlen($arChildItem["NAME"], "UTF8");
				$columnHeight -= ($strlen>22?$chFirstLevel1:$chFirstLevel2);
				//	if($arItem['NAME'] == "Инструмент, оборудование, расходные материалы") {printArr($arChildItem['NAME']);printArr($columnHeight);}
				
				if($columnHeight <= 0 && !$arChildItem["CHILD"]) {
					$columnHeight = $heightWrapMenu - 40;
					$arResult[$key]["CHILD"][$chKey]['endColumn'] = true;
				}
				
				if($ch) {
					$columnHeight -= 20;
				}
				$endColumn = false;
				if($arChildItem["CHILD"]){
				$ch = count($arChildItem["CHILD"]);
					foreach($arChildItem["CHILD"] as $chKey1 => $arChildItem1){
						$strlen = iconv_strlen($arChildItem1["NAME"], "UTF8");
						if($strlen > 32)
							$columnHeight -= $chSecondLevel3;
						elseif($strlen > 22)
							$columnHeight -= $chSecondLevel2;
						else
							$columnHeight -= $chSecondLevel;
						
						
						#	if($arItem['NAME'] == "Участок, сад, огород") {varDump($arChildItem1['NAME'],$columnHeight);}
						if($columnHeight <= 0) {
							/*$columnHeight = $heightWrapMenu - 40;
							$arResult[$key]["CHILD"][$chKey]["CHILD"][$chKey1]['endColumn'] = true;*/
							$endColumn = true;
						}
					}
				}

				if($endColumn) {
					$columnHeight = $heightWrapMenu - 40 - ($ch*$chSecondLevel);
					$arResult[$key]["CHILD"][$chKey]['endColumn'] = true;
				}

			}
		}
	}
		 //printArr($arResult);
}
?>