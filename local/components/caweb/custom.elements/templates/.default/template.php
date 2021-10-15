<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);?>
<div class="similar_products_wrapp main_temp custom_elements">
    <h3><?=$arParams['TITLE']?></h3>
    <div class="module-products-corusel product-list-items catalog">
        <?$APPLICATION->IncludeComponent(
            "caweb:catalog.top",
            "products_slider",
            array(
                "ACTION_VARIABLE" => "action",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "BASKET_URL" => SITE_DIR."basket/",
                "CACHE_FILTER" => "Y",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "3600000",
                "CACHE_TYPE" => "Y",
                "COMPARE_PATH" => "",
                "COMPATIBLE_MODE" => "Y",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "CONVERT_CURRENCY" => "N",
                "CUSTOM_FILTER" => $arParams['~CUSTOM_FILTER'],
                "DETAIL_URL" => "",
                "DISPLAY_COMPARE" => "N",
                "DISPLAY_WISH_BUTTONS" => "Y",
                "ELEMENT_COUNT" => "20",
                "ELEMENT_SORT_FIELD" => "sort",
                "ELEMENT_SORT_FIELD2" => "id",
                "ELEMENT_SORT_ORDER" => "asc",
                "ELEMENT_SORT_ORDER2" => "desc",
                "FILTER_NAME" => "",
                "HIDE_NOT_AVAILABLE" => "Y",
                "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                "IBLOCK_ID" => "16",
                "IBLOCK_TYPE" => "1c_catalog",
                "INIT_SLIDER" => "Y",
                "LINE_ELEMENT_COUNT" => "",
                "OFFERS_CART_PROPERTIES" => array(
                ),
                "OFFERS_FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "OFFERS_LIMIT" => "10",
                "OFFERS_PROPERTY_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_FIELD2" => "id",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_ORDER2" => "desc",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRICE_CODE" => array(
                    0 => "ТО",
                    1 => "СО",
                    2 => "КП",
                    3 => "С",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPERTIES" => array(
                ),
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SECTION_URL" => "",
                "SEF_MODE" => "N",
                "SHOW_BUY_BUTTONS" => "",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "SHOW_MEASURE" => "Y",
                "SHOW_MEASURE_WITH_RATIO" => "N",
                "SHOW_OLD_PRICE" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "SHOW_RATING" => "Y",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "COMPONENT_TEMPLATE" => "products_slider"
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );?>
    </div>
</div>
