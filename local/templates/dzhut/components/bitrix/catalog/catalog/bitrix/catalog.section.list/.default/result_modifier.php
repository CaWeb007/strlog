<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

// Модифицируем пункты для разбивки на колонки 
/* $arCopy = $arResult["SECTIONS"]; 
foreach ($arResult["SECTIONS"] as $arSection) 
{ 
   if ($arSection["IBLOCK_SECTION_ID"] == $arParams["SECTION_ID"]) 
   { 
      $SUBITEMS = array(); 
      foreach ($arCopy as $subItem) 
      { 
         if ($subItem["IBLOCK_SECTION_ID"] == $arSection["ID"]) 
				$SUBITEMS[] = $subItem; 
      } 
      $arSection["ITEMS"] = $SUBITEMS; 
      $SECT[] = $arSection; 
   } 
} 
$arResult["SECT"] = $SECT; 
 */

?> 