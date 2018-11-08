<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <?
    foreach($arItem["PROPERTIES"]["YANDEX_COORDS"]["VALUE"] as $key => $value){
        $arCoords = explode(',', $value);
        $arItem['POS']['yandex_scale'] = "10";
        $arItem['POS']['yandex_lat'] = $arCoords[0];
        $arItem['POS']['yandex_lon'] = $arCoords[1];
        $arItem['POS']['PLACEMARKS'][] = array(
            'LON' => $arCoords[1],
            'LAT' => $arCoords[0],
            'TEXT' => "Можно добавить данные",
        );
    }
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:map.yandex.view",
        ".default",
        array(
            "KEY" => $MAP_KEY,
            "INIT_MAP_TYPE" => "MAP",
            "MAP_DATA" => serialize($arItem['POS']),
            "MAP_WIDTH" => "100%",
            "MAP_HEIGHT" => "500",
            "CONTROLS" => array(
                0 => "ZOOM",
                1 => "MINIMAP",
                2 => "TYPECONTROL",
                3 => "SCALELINE",
            ),
            "OPTIONS" => array(
                0 => "ENABLE_SCROLL_ZOOM",
                1 => "ENABLE_DBLCLICK_ZOOM",
                2 => "ENABLE_DRAGGING",
            ),
            "MAP_ID" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );?>
<?endforeach;?>
</div>
