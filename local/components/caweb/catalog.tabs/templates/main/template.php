<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**@var $arResult array*/
/**@var $arParams array*/
/**@var $this \CBitrixComponentTemplate*/
$class_block="s_".$this->randString();
$col=4;
$GLOBALS['PRODUCT_TABS_FILTER'] = array(
    array(
        "LOGIC" => "AND",
        array("!DETAIL_PICTURE" => false),
        array("PROPERTY_ORDER_ITEM_VALUE" => false),
        array("CATALOG_AVAILABLE" => "Y")
    )
)
?>
<div class="custom-tabs-container">
        <div class="custom-tabs-top-line">
            <?if($arParams['LINE_1']):?>
                <span class="custom-tabs-top-line-title"><?=$arParams['LINE_1']?></span>
            <?endif;?>
            <?if($arParams['LINE_2']):?>
                <span class="custom-tabs-top-line-description"><?=$arParams['LINE_2']?></span>
            <?endif;?>
        </div>
        <div class="tab_slider_wrapp specials <?=$class_block;?> best_block clearfix custom-tabs">
            <div class="top_blocks">
                <div>
                    <ul class="tabs" id="slick-tabs">
                        <?$firstStep = true;?>
                        <?foreach ($arResult as $key => $arItem):?>
                            <li data-code="<?=$key?>" <?=($key==1 ? "class='cur'" : "")?>>
                                <?=$arItem['TITLE']?>
                            </li>
                            <?$firstStep = false;?>
                        <?endforeach;?>
                    </ul>
                </div>
                <div>
                    <ul class="slider_navigation top custom_flex border">
                        <?$i=1;
                        foreach($arResult as $key=>$arItem):?>
                            <li class="tabs_slider_navigation <?=$key?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$key?>"></li>
                            <?$i++;?>
                        <?endforeach;?>
                    </ul>
                </div>
            </div>
            <ul class="tabs_content custom-tabs-content" style="min-height: 400px">
                <div class="loader"></div>
                <?$firstStep = true;?>
                <?foreach ($arResult as $key => $arItem):?>
                    <li class="tab <?=$key?>_wrapp <?=($firstStep ? "cur" : "");?> custom-tab" data-code="<?=$key?>" data-col="<?=$col;?>">
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
                                "CUSTOM_FILTER" => $arItem['FILTER'],
                                "DETAIL_URL" => "",
                                "DISPLAY_COMPARE" => "N",
                                "DISPLAY_WISH_BUTTONS" => "Y",
                                "ELEMENT_COUNT" => "20",
                                "ELEMENT_SORT_FIELD" => "PRODUCT_WITH_STOCK",
                                "ELEMENT_SORT_FIELD2" => "PROPERTY_FLAG",
                                "ELEMENT_SORT_FIELD3" => "sort",
                                "ELEMENT_SORT_ORDER" => "desc",
                                "ELEMENT_SORT_ORDER2" => "desc",
                                "ELEMENT_SORT_ORDER3" => "asc",
                                "FILTER_NAME" => "PRODUCT_TABS_FILTER",
                                "HIDE_NOT_AVAILABLE" => "L",
                                "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                                "IBLOCK_ID" => "16",
                                "IBLOCK_TYPE" => "1c_catalog",
                                "INIT_SLIDER" => "N",
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
                    </li>
                <?$firstStep = false;?>
                <?endforeach;?>
            </ul>
        </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.custom-tabs.<?=$class_block;?> .tabs > li').first().addClass('cur');
        $('.custom-tabs.<?=$class_block;?> .slider_navigation > li').first().addClass('cur');
        $('.custom-tabs.<?=$class_block;?> .tabs_content > li').first().addClass('cur');

        var flexsliderItemWidth = 220;
        var flexsliderItemMargin = 12;

        var sliderWidth = $('.custom-tabs.<?=$class_block;?>').outerWidth();
        var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
        $('.custom-tabs.<?=$class_block;?> .tabs_content > li.cur').flexslider({
            animation: 'slide',
            selector: '.specials_slider > .catalog_item',
            slideshow: false,
            animationSpeed: 600,
            directionNav: true,
            controlNav: false,
            pauseOnHover: true,
            animationLoop: true,
            itemWidth: flexsliderItemWidth,
            itemMargin: flexsliderItemMargin,
            minItems: flexsliderMinItems,
            controlsContainer: '.custom-tabs.<?=$class_block;?>  .tabs_slider_navigation.cur',
            namespace: 'customflex-',
            prevText: "<i class=\"fa fa-angle-left\"></i>",
            nextText: "<i class=\"fa fa-angle-right\"></i>",
            start: function(slider){
                slider.find('li').css('opacity', 1);
                setHeightBlockSlider2('.custom-tabs.<?=$class_block;?>');
                $('.custom-tabs.<?=$class_block;?> .custom-tabs-content > .loader').hide()
            }
        });

        $('.custom-tabs.<?=$class_block;?> .tabs > li').on('click', function(){
            var sliderIndex = $(this).index();
            if(!$('.custom-tabs.<?=$class_block;?> .tabs_content > li.cur .customflex-viewport').length){
                $('.custom-tabs.<?=$class_block;?> .tabs_content > li.cur').flexslider({
                    animation: 'slide',
                    selector: '.specials_slider > .catalog_item',
                    slideshow: false,
                    animationSpeed: 600,
                    directionNav: true,
                    controlNav: false,
                    pauseOnHover: true,
                    animationLoop: true,
                    itemWidth: flexsliderItemWidth,
                    itemMargin: flexsliderItemMargin,
                    minItems: flexsliderMinItems,
                    controlsContainer: '.custom-tabs.<?=$class_block;?> .tabs_slider_navigation.cur',
                    namespace: 'customflex-',
                    prevText: "<i class=\"fa fa-angle-left\"></i>",
                    nextText: "<i class=\"fa fa-angle-right\"></i>",
                    start: function (slider) {
                        slider.find('li').css('opacity', 1);
                        setHeightBlockSlider2('.custom-tabs.<?=$class_block;?>');
                        //$('.custom-tabs.<?=$class_block;?> .custom-tabs-content > .loader').hide()

                    }
                });
            }
            var h = $('.custom-tabs.<?=$class_block;?> .tabs_content  .tab.cur').attr('data-unhover') * 1;
            $('.custom-tabs.<?=$class_block;?> .tabs_content').stop().animate({'height': h}, 300);
        });
        $(document).on({
            mouseover: function(e){
                var w = $(window).width() * 1;
                if (w < 860) return;
                var tabsContentHover = $(this).closest('.tab').attr('data-hover') * 1;
                $(this).closest('.tab').fadeTo(100, 1);
                $(this).closest('.tab').stop().css({'height': tabsContentHover});
                $(this).find('.buttons_block').fadeIn(450, 'easeOutCirc');
            },
            mouseleave: function(e){
                var w = $(window).width() * 1;
                if (w < 860) return;
                var tabsContentUnhoverHover = $(this).closest('.tab').attr('data-unhover') * 1;
                $(this).closest('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
                $(this).find('.buttons_block').stop().fadeOut(233);
            }
        }, '.<?=$class_block;?> .tabs_slider > li');
    })
</script>