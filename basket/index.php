<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
//Pr($_SESSION['CAWEB_DISCOUNT']);
?><?$APPLICATION->IncludeComponent("caweb:sale.basket.basket", "", Array(
	"ACTION_VARIABLE" => "action",	// Название переменной действия
		"ADDITIONAL_PICT_PROP_14" => "-",
		"ADDITIONAL_PICT_PROP_15" => "-",	// Дополнительная картинка [Торговые предложения]
		"ADDITIONAL_PICT_PROP_16" => "-",	// Дополнительная картинка [Стройлогистика]
		"ADDITIONAL_PICT_PROP_17" => "-",	// Дополнительная картинка [Пакет предложений (Стройлогистика)]
		"ADDITIONAL_PICT_PROP_23" => "-",	// Дополнительная картинка [Пакет предложений (Стройлогистика)]
		"ADDITIONAL_PICT_PROP_24" => "-",	// Дополнительная картинка [Стройлогистика некондиция]
		"ADDITIONAL_PICT_PROP_25" => "-",	// Дополнительная картинка [Пакет предложений (Стройлогистика некондиция)]
		"AJAX_MODE_CUSTOM" => "Y",
		"AUTO_CALCULATION" => "Y",	// Автопересчет корзины
		"BASKET_IMAGES_SCALING" => "adaptive",	// Режим отображения изображений товаров
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "TYPE",
			6 => "PRICE",
			7 => "QUANTITY",
			8 => "SUM",
		),
		"COLUMNS_LIST_EXT" => array(	// Выводимые колонки
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "SUM",
		),
		"COLUMNS_LIST_MOBILE" => array(	// Колонки, отображаемые на мобильных устройствах
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "SUM",
		),
		"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
		"CORRECT_RATIO" => "N",	// Автоматически рассчитывать количество товара кратное коэффициенту
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"DEFERRED_REFRESH" => "N",	// Использовать механизм отложенной актуализации данных товаров с провайдером
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",	// Расположение процента скидки
		"DISPLAY_MODE" => "extended",	// Режим отображения корзины
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
		"GIFTS_CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
		"GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
		"GIFTS_PAGE_ELEMENT_COUNT" => "10",	// Количество элементов в строке
		"GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
		"GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
		"GIFTS_SHOW_NAME" => "Y",	// Показывать название
		"GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
		"HIDE_COUPON" => "N",	// Спрятать поле ввода купона
		"LABEL_PROP" => "",	// Свойства меток товара
		"LABEL_PROP_MOBILE" => "",
		"LABEL_PROP_POSITION" => "",
		"OFFERS_PROPS" => "",	// Свойства, влияющие на пересчет корзины
		"PATH_TO_BASKET" => "basket/",
		"PATH_TO_ORDER" => SITE_DIR."order/",	// Страница оформления заказа
		"PICTURE_HEIGHT" => "100",
		"PICTURE_WIDTH" => "100",
		"PRICE_DISPLAY_MODE" => "Y",	// Отображать цену в отдельной колонке
		"PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",	// Порядок отображения блоков товара
		"QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки рядом с изображением
		"SHOW_FAST_ORDER_BUTTON" => "N",
		"SHOW_FILTER" => "Y",	// Отображать фильтр товаров
		"SHOW_FULL_ORDER_BUTTON" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_RESTORE" => "Y",	// Разрешить восстановление удалённых товаров
		"TEMPLATE_THEME" => "blue",	// Цветовая тема
		"TOTAL_BLOCK_DISPLAY" => array(	// Отображение блока с общей информацией по корзине
			0 => "bottom",
		),
		"USE_DYNAMIC_SCROLL" => "Y",	// Использовать динамическую подгрузку товаров
		"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
		"USE_GIFTS" => "Y",	// Показывать блок "Подарки"
		"USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
		"USE_PRICE_ANIMATION" => "Y",	// Использовать анимацию цен
        'KP_PRICE_ID' => '11'
	),
	false
);?>
<div class="print_basket basket_print_desc">
	<?$APPLICATION->IncludeFile(SITE_DIR."include/basket_print_desc.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("BASKET_PRINT_TEXT")));?>
</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/comp_basket_bigdata.php"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>