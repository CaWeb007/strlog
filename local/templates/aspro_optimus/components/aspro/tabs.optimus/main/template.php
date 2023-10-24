<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);
	
	$class_block="s_".$this->randString();
	$arTab=array();
	$col=4;
	if($arParams["LINE_ELEMENT_COUNT"]>=3 && $arParams["LINE_ELEMENT_COUNT"]<4)
		$col=3;
	if($arResult["SHOW_SLIDER_PROP"]){?>
		<div class="tab_slider_wrapp specials <?=$class_block;?> best_block clearfix">
			<div class="top_blocks">
				<ul class="tabs">
					<!--li data-code="MAIN" class="cur"><span>Лучшие предложения</span></li-->
					<?$i=1;
					foreach($arResult["TABS"] as $code=>$title):?>
						<?if($code == "1852c985-9eb6-11e8-80f3-00155d5b5d16"):?>
					<li data-code="<?=$code?>" <?=($i==1 ? "class='cur'" : "")?>><span>Лучшие предложения<?//=$title;?></span></li>
						<?break;endif;?>
						<?$i++;?>
					<?endforeach;?>

					<li class="stretch"></li>
				</ul>
			</div>
			<ul class="tabs_content">
				<?$j=1;?>
                <?
                    /*region UnsetProductsForHome*/
                    // product for home unset
                    $sectionsNot[] = 2007;
                    $arParentSection = CIBlockSection::GetByID(2007)->Fetch();
                    $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
                    $arSect = \COptimusCache::CIBlockSection_GetList(array('left_margin' => 'asc'),$arFilter);
                    foreach ($arSect as $item) $sectionsNot[] = (int)$item['ID'];
                    // product for garden.... unset
                    $sectionsNot[] = 2075;
                    $arParentSection = CIBlockSection::GetByID(2075)->Fetch();
                    $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
                    $arSect = \COptimusCache::CIBlockSection_GetList(array('left_margin' => 'asc'),$arFilter);
                    foreach ($arSect as $item) $sectionsNot[] = (int)$item['ID'];
                    // product for крепеж.... unset
                    $sectionsNot[] = 1895;
                    $arParentSection = CIBlockSection::GetByID(1895)->Fetch();
                    $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
                    $arSect = \COptimusCache::CIBlockSection_GetList(array('left_margin' => 'asc'),$arFilter);
                    foreach ($arSect as $item) $sectionsNot[] = (int)$item['ID'];

                    $GLOBALS[$arParams['FILTER_NAME']]['!SECTION_ID'] = $sectionsNot;
                    $GLOBALS[$arParams['FILTER_NAME']]['!PROPERTY_FLAG_VALUE'] = 'LIKVIDATION';
                    $GLOBALS[$arParams['FILTER_NAME']]['!DETAIL_PICTURE'] = false;
                    $GLOBALS[$arParams['FILTER_NAME']]['!PROPERTY_ORDER_ITEM_VALUE'] = 'Да';
                    /*endregion*/
                ?>
                <?$GLOBALS[$arParams["FILTER_NAME"]][0]=['LOGIC'=>'OR'];?>
                <?foreach($arResult["TABS"] as $code=>$title){?>
							<?
							if($code != "1852c989-9eb6-11e8-80f3-00155d5b5d16"){
								$GLOBALS[$arParams["FILTER_NAME"]][0][]=array("PROPERTY_FLAG_VALUE" => array($title));
								if($arParams["SECTION_ID"]){
									$GLOBALS[$arParams["FILTER_NAME"]]["SECTION_ID"]=$arParams["SECTION_ID"];
									$GLOBALS[$arParams["FILTER_NAME"]]["INCLUDE_SUBSECTIONS"] = "Y"; 
								}
							}			
							?>
				<?}?>
				<?foreach($arResult["TABS"] as $code=>$title){?>
				<?if($code == "1852c985-9eb6-11e8-80f3-00155d5b5d16"):?>
					<li class="tab <?=$code?>_wrapp <?=($j++ ==1 ? "cur" : "");?>" data-code="<?=$code?>" data-col="<?=$col;?>">
						<div class="tabs_slider <?=$code?>_slides wr">

							<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "catalog_block_front2", Array(
                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],	// Тип инфоблока
                                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],	// Инфоблок
                                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],	// По какому полю сортируем элементы
                                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],	// Порядок сортировки элементов
                                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],	// Поле для второй сортировки элементов
                                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],	// Порядок второй сортировки элементов
                                    "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],	// Свойства
                                    "SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
                                    "META_KEYWORDS" => $arParams["META_KEYWORDS"],	// Установить ключевые слова страницы из свойства
                                    "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],	// Установить описание страницы из свойства
                                    "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],	// Установить заголовок окна браузера из свойства
                                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],	// Устанавливать в заголовках ответа время модификации страницы
                                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],	// Показывать элементы подразделов раздела
                                    "BASKET_URL" => $arParams["BASKET_URL"],	// URL, ведущий на страницу с корзиной покупателя
                                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],	// Название переменной, в которой передается действие
                                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],	// Название переменной, в которой передается код товара для покупки
                                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],	// Название переменной, в которой передается код группы
                                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],	// Название переменной, в которой передается количество товара
                                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],	// Название переменной, в которой передаются характеристики товара
                                    "FILTER_NAME" => $arParams["FILTER_NAME"],	// Имя массива со значениями фильтра для фильтрации элементов
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],	// Тип кеширования
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],	// Время кеширования (сек.)
                                    "CACHE_FILTER" => "Y",	// Кешировать при установленном фильтре
                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],	// Учитывать права доступа
                                    "SET_TITLE" => $arParams["SET_TITLE"],	// Устанавливать заголовок страницы
                                    "MESSAGE_404" => $arParams["MESSAGE_404"],	// Сообщение для показа (по умолчанию из компонента)
                                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],	// Устанавливать статус 404
                                    "SHOW_404" => $arParams["SHOW_404"],	// Показ специальной страницы
                                    "FILE_404" => $arParams["FILE_404"],
                                    "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],	// Разрешить сравнение товаров
                                    "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                                    "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],	// Количество элементов на странице
                                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],	// Количество элементов выводимых в одной строке таблицы
                                    "PRICE_CODE" => $arParams["PRICE_CODE"],	// Тип цены
                                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],	// Использовать вывод цен с диапазонами
                                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],	// Выводить цены для количества
                                    "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                                    "SHOW_RATING" => $arParams["SHOW_RATING"],
                                    "SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
                                    "SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
                                    "SALE_STIKER" => $arParams["SALE_STIKER"],
                                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],	// Включать НДС в цену
                                    "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],	// Разрешить указание количества товара
                                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"])?$arParams["ADD_PROPERTIES_TO_BASKET"]:""),	// Добавлять в корзину свойства товаров и предложений
                                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"])?$arParams["PARTIAL_PRODUCT_PROPERTIES"]:""),	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],	// Характеристики товара
                                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],	// Выводить над списком
                                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],	// Выводить под списком
                                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],	// Название категорий
                                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],	// Выводить всегда
                                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],	// Шаблон постраничной навигации
                                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],	// Использовать обратную навигацию
                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],	// Время кеширования страниц для обратной навигации
                                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],	// Показывать ссылку "Все"
                                    "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],	// Включить обработку ссылок
                                    "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                                    "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                                    "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                                    "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],	// Максимальное количество предложений для показа (0 - все)
                                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],	// ID раздела
                                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],	// Код раздела
                                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],	// URL, ведущий на страницу с содержимым раздела
                                    "DETAIL_URL" => $arParams["DETAIL_URL"],	// URL, ведущий на страницу с содержимым элемента раздела
                                    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],	// Использовать основной раздел для показа элемента
                                    "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],	// Показывать цены в одной валюте
                                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                                    "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],	// Недоступные товары
                                    "SHOW_ALL_WO_SECTION" => $arParams["SHOW_ALL_WO_SECTION"],	// Показывать все элементы, если не указан раздел
                                    "LABEL_PROP" => $arParams["LABEL_PROP"],
                                    "ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
                                    "PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],
                                    "OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
                                    "OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
                                    "PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
                                    "SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
                                    "MESS_BTN_BUY" => $arParams["MESS_BTN_BUY"],
                                    "MESS_BTN_ADD_TO_BASKET" => $arParams["MESS_BTN_ADD_TO_BASKET"],
                                    "MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
                                    "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
                                    "MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
                                    "TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"])?$arParams["TEMPLATE_THEME"]:""),
                                    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
                                    "ADD_TO_BASKET_ACTION" => $basketAction,
                                    "SHOW_CLOSE_POPUP" => isset($arParams["COMMON_SHOW_CLOSE_POPUP"])?$arParams["COMMON_SHOW_CLOSE_POPUP"]:"",
                                    "COMPARE_PATH" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
                                    "BACKGROUND_IMAGE" => (isset($arParams["SECTION_BACKGROUND_IMAGE"])?$arParams["SECTION_BACKGROUND_IMAGE"]:""),	// Установить фоновую картинку для шаблона из свойства
                                    "DISABLE_INIT_JS_IN_COMPONENT" => (isset($arParams["DISABLE_INIT_JS_IN_COMPONENT"])?$arParams["DISABLE_INIT_JS_IN_COMPONENT"]:""),	// Не подключать js-библиотеки в компоненте

                                        "SHOW_ONE_CLICK_BUY" => "Y",
                                ),
                                false,
                                array(
                                "HIDE_ICONS" => "N"
                                )
                            );?>
						</div>
					</li>
				<?endif?>
				<?}?>
			</ul>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.catalog_block .catalog_item_wrapp .catalog_item .item_info:visible .item-title').sliceHeight({item:'.catalog_item'});
				$('.catalog_block .catalog_item_wrapp .catalog_item .item_info:visible').sliceHeight({classNull: '.footer_button', item:'.catalog_item'});
				$('.catalog_block .catalog_item_wrapp .catalog_item:visible').sliceHeight({classNull: '.footer_button', item:'.catalog_item'});
			});
		</script>
	<?}?>