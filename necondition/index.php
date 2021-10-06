<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Уцененные товары");
$APPLICATION->SetPageProperty("keywords", "Уцененные товары");
$APPLICATION->SetPageProperty("title", "Уцененные товары");
$APPLICATION->SetTitle("Уцененные товары");
?>

<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main", 
	array(
		"IBLOCK_TYPE" => "1c_catalog",
		"IBLOCK_ID" => "24",
		"HIDE_NOT_AVAILABLE" => "Y",
		"BASKET_URL" => "/basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "1026",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/necondition/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "OPTIMUS_SMART_FILTER",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "BASE",
			1 => "ТО",
			2 => "СО",
			3 => "КП",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "COLOR",
			6 => "CML2_LINK",
			7 => "",
		),
		"USE_REVIEW" => "Y",
		"MESSAGES_PER_PAGE" => "10",
		"USE_CAPTCHA" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"FORUM_ID" => "1",
		"URL_TEMPLATES_READ" => "",
		"SHOW_LINK_TO_FORUM" => "Y",
		"POST_FIRST_MESSAGE" => "N",
		"USE_COMPARE" => "Y",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "NAME",
			1 => "TAGS",
			2 => "SORT",
			3 => "PREVIEW_PICTURE",
			4 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "CML2_BASE_UNIT",
			2 => "CML2_MANUFACTURER",
			3 => "BRAND",
			4 => "PROP_2033",
			5 => "COLOR_REF2",
			6 => "PROP_159",
			7 => "PROP_2052",
			8 => "PROP_2027",
			9 => "PROP_2053",
			10 => "PROP_2083",
			11 => "PROP_2049",
			12 => "PROP_2026",
			13 => "PROP_2044",
			14 => "PROP_162",
			15 => "PROP_2065",
			16 => "PROP_2054",
			17 => "PROP_2017",
			18 => "PROP_2055",
			19 => "PROP_2069",
			20 => "PROP_2062",
			21 => "PROP_2061",
			22 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
			1 => "ТО",
			2 => "СО",
			3 => "КП",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"SHOW_TOP_ELEMENTS" => "Y",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_LIST_PICTURES" => "Y",
		"PAGE_ELEMENT_COUNT" => "20",
		"LINE_ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "catalog_PRICE_11",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "catalog_PRICE_11",
		"ELEMENT_SORT_ORDER2" => "asc",
		"LIST_PROPERTY_CODE" => array(
			0 => "NAGRUZKA_T",
			1 => "STEKLOVOLOKNISTAYA_OSNOVA_G_M2",
			2 => "TEMPERATURA_NACHALA_OTKRYVANIYA_POLNOGO_ZAKRYTIYA_",
			3 => "VES_KG",
			4 => "DIAMETR_OBOYMY_MM",
			5 => "TIP_BITUMNOGO_VYAZHUSHCHEGO",
			6 => "VYSOTA_OBOYMY_MM",
			7 => "NIZHNEE_POKRYTIE",
			8 => "TROS_SHT",
			9 => "ZASHCHITNYY_SLOY_MM",
			10 => "TALREP_SHT",
			11 => "TEPLOSTOYKOST_S",
			12 => "KARABIN_SHT",
			13 => "METROV_V_UPAKOVKE",
			14 => "CML2_MANUFACTURER",
			15 => "SOSTAV",
			16 => "CML2_ATTRIBUTES",
			17 => "BRAND",
			18 => "ZAZHIM_M3_SHT",
			19 => "RULONOV_V_UPAKOVKE",
			20 => "METROV_V_RULONE",
			21 => "RAZMERY_VES",
			22 => "UGOLOK_25KH25_MM_SHT",
			23 => "KONSTRUKTSIYA",
			24 => "SAMOREZ_4_2KH13_SHT",
			25 => "MATERIAL",
			26 => "MINIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			27 => "DUGI",
			28 => "MAKSIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			29 => "RASSTOYANIE_MEZHDU_DUGAMI",
			30 => "TEMPERATURA_NACHALA_ZAKRYVANIYA_POLNOGO_OTKRYTIYA",
			31 => "STRINGERY",
			32 => "TEMPERATURA_EKSPLUATATSII",
			33 => "OKRASKA",
			34 => "PROIZVODITEL",
			35 => "MESTO_KREPLENIYA",
			36 => "SOEDINENIE_ELEMENTOV",
			37 => "KREPEZH_DLYA_SBORKI_FURNITURA",
			38 => "RAZMER_M",
			39 => "KREPEZH_DLYA_POLIKARBONATA",
			40 => "PLOSHCHAD_M2",
			41 => "INSTRUKTSIYA_PO_SBORKE",
			42 => "KOLICHESTVO_SOT",
			43 => "GARANTIYA",
			44 => "KOLICHESTVO_V_UPAKOVKE_SHT",
			45 => "TORTSY",
			46 => "DLINA_NOZHKI_MM",
			47 => "KOLICHESTVO_GNEZD_SHT",
			48 => "VYSOTA_MM",
			49 => "PLOTNOST_G_M2",
			50 => "MOROZOSTOYKOST",
			51 => "MOSHCHNOST_VT",
			52 => "VYVOD",
			53 => "PROIZVODITEL_1",
			54 => "SHIRINA_MM",
			55 => "DIAMETR_MM",
			56 => "TOLSHCHINA_MM",
			57 => "PLOTNOST_KG_M2_1",
			58 => "KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K",
			59 => "SHIRINA_MM_1",
			60 => "SHIRINA_RULONA_MM",
			61 => "DLINA_MM_1",
			62 => "TIP",
			63 => "NALICHIE",
			64 => "RAZMER_MM",
			65 => "RAZMER_SM",
			66 => "TSVET",
			67 => "POLEZNAYA_DLINA_M",
			68 => "OBEM_ML",
			69 => "TIP_POSYPKI",
			70 => "KLASS_PROCHNOSTI",
			71 => "TSENA_ZA_M2",
			72 => "OBEM_M3",
			73 => "POKRYTIE",
			74 => "DLINA_M",
			75 => "HIT",
			76 => "BREND",
			77 => "TOLSHCHINA_MM_1",
			78 => "VES_KG_M2",
			79 => "PLOTNOST_KG_M2",
			80 => "UF_ZASHCHITA_MKR",
			81 => "VES_LISTA_KG",
			82 => "VYSOTA_M",
			83 => "_1",
			84 => "DLINA_MM",
			85 => "DIAMETR_MM_1",
			86 => "RAZMERY_LISTA_M",
			87 => "OBEM_L",
			88 => "MAKSIMALNAYA_NAGRUZKA",
			89 => "RAZMER",
			90 => "SHAG_MM",
			91 => "SHIRINA_LEZVIYA_MM",
			92 => "SHIRINA_M",
			93 => "179",
			94 => "rating",
			95 => "PROP_2033",
			96 => "COLOR_REF2",
			97 => "PROP_159",
			98 => "PROP_2052",
			99 => "PROP_2027",
			100 => "PROP_2053",
			101 => "PROP_2083",
			102 => "PROP_2049",
			103 => "PROP_2026",
			104 => "PROP_2044",
			105 => "PROP_162",
			106 => "PROP_2065",
			107 => "PROP_2054",
			108 => "PROP_2017",
			109 => "PROP_2055",
			110 => "PROP_2069",
			111 => "PROP_2062",
			112 => "PROP_2061",
			113 => "CML2_LINK",
			114 => "",
		),
		"INCLUDE_SUBSECTIONS" => "N",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "CML2_LINK",
			2 => "DETAIL_PAGE_URL",
			3 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",
		"SORT_BUTTONS" => array(
			0 => "POPULARITY",
			1 => "PRICE",
			2 => "QUANTITY",
		),
		"SORT_PRICES" => "КП",
		"DEFAULT_LIST_TEMPLATE" => "table",
		"SECTION_DISPLAY_PROPERTY" => "",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SECTION_SIBLINGS" => "Y",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "VIDEO_YOUTUBE",
			2 => "PROP_2033",
			3 => "COLOR_REF2",
			4 => "PROP_159",
			5 => "PROP_2052",
			6 => "PROP_2027",
			7 => "PROP_2053",
			8 => "PROP_2083",
			9 => "PROP_2049",
			10 => "PROP_2026",
			11 => "PROP_2044",
			12 => "PROP_162",
			13 => "PROP_2065",
			14 => "PROP_2054",
			15 => "PROP_2017",
			16 => "PROP_2055",
			17 => "PROP_2069",
			18 => "PROP_2062",
			19 => "PROP_2061",
			20 => "RECOMMEND",
			21 => "STOCK",
			22 => "VIDEO",
			23 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "DETAIL_PAGE_URL",
			4 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "",
		),
		"PROPERTIES_DISPLAY_LOCATION" => "TAB",
		"SHOW_BRAND_PICTURE" => "Y",
		"SHOW_ASK_BLOCK" => "Y",
		"ASK_FORM_ID" => "2",
		"SHOW_ADDITIONAL_TAB" => "Y",
		"PROPERTIES_DISPLAY_TYPE" => "TABLE",
		"SHOW_KIT_PARTS" => "Y",
		"SHOW_KIT_PARTS_PRICES" => "Y",
		"LINK_IBLOCK_TYPE" => "1c_catalog",
		"LINK_IBLOCK_ID" => "16",
		"LINK_PROPERTY_SID" => "BRAND",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"ALSO_BUY_ELEMENT_COUNT" => "5",
		"ALSO_BUY_MIN_BUYES" => "2",
		"USE_STORE" => "Y",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "Y",
		"MIN_AMOUNT" => "10",
		"STORE_PATH" => "/contacts/stores/#store_id#/",
		"MAIN_TITLE" => "Наличие на складах",
		"MAX_AMOUNT" => "20",
		"USE_ONLY_MAX_AMOUNT" => "Y",
		"OFFERS_SORT_FIELD" => "shows",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "shows",
		"OFFERS_SORT_ORDER2" => "asc",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"IBLOCK_STOCK_ID" => "12",
		"SHOW_QUANTITY" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_QUANTITY_COUNT" => "Y",
		"USE_RATING" => "Y",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DEFAULT_COUNT" => "1",
		"SHOW_HINTS" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"STORES" => array(
			0 => "1",
			1 => "",
		),
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"TOP_ELEMENT_COUNT" => "8",
		"TOP_LINE_ELEMENT_COUNT" => "4",
		"TOP_ELEMENT_SORT_FIELD" => "shows",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPONENT_TEMPLATE" => "main",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"SHOW_DEACTIVATED" => "N",
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "10",
		"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
		"OFFER_TREE_PROPS" => array(
		),
		"USE_BIG_DATA" => "N",
		"BIG_DATA_RCM_TYPE" => "similar_view",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"VIEWED_ELEMENT_COUNT" => "20",
		"VIEWED_BLOCK_TITLE" => "Ранее вы смотрели",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"SKU_DETAIL_ID" => "oid",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"SET_LAST_MODIFIED" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"AJAX_FILTER_CATALOG" => "N",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DISPLAY_ELEMENT_SLIDER" => "10",
		"SHOW_ONE_CLICK_BUY" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"OFFER_HIDE_NAME_PROPS" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "Y",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_DISCOUNT_TIME" => "N",
		"SHOW_RATING" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"SHOW_UNABLE_SKU_PROPS" => "Y",
		"DETAIL_OFFERS_LIMIT" => "0",
		"DETAIL_EXPANDABLES_TITLE" => "Аксессуары",
		"DETAIL_ASSOCIATED_TITLE" => "Похожие товары",
		"COMPATIBLE_MODE" => "Y",
		"TEMPLATE_THEME" => "blue",
		"LABEL_PROP" => "",
		"PRODUCT_DISPLAY_MODE" => "N",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "N",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_POSITION" => "top left",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"TOP_VIEW_MODE" => "SECTION",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"LIST_PROPERTY_CODE_MOBILE" => "",
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => "",
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => "",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"MESS_PRICE_RANGES_TITLE" => "Цены",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"MESS_COMMENTS_TAB" => "Комментарии",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"LAZY_LOAD" => "N",
		"LOAD_ON_SCROLL" => "N",
		"SECTION_SORT_VALUES" => array(
			0 => "RAZMERY_VES",
			1 => "METROV_V_UPAKOVKE",
			2 => "KARABIN_SHT",
			3 => "ZAZHIM_M3_SHT",
			4 => "TEPLOSTOYKOST_S",
			5 => "RAZMER_M",
			6 => "PLOSHCHAD_M2",
			7 => "KOLICHESTVO_SOT",
			8 => "KOLICHESTVO_GNEZD_SHT",
			9 => "VYSOTA_MM",
			10 => "KOLICHESTVO_V_UPAKOVKE_SHT",
			11 => "MOSHCHNOST_VT",
			12 => "DLINA_NOZHKI_MM",
			13 => "PLOTNOST_G_M2",
			14 => "PROIZVODITEL_1",
			15 => "TOLSHCHINA_MM",
			16 => "PLOTNOST_KG_M2_1",
			17 => "SHIRINA_MM",
			18 => "DIAMETR_MM",
			19 => "RULONOV_V_UPAKOVKE",
			20 => "METROV_V_RULONE",
			21 => "SHIRINA_MM_1",
			22 => "SHIRINA_RULONA_MM",
			23 => "DLINA_MM_1",
			24 => "RAZMER_MM",
			25 => "RAZMER_SM",
			26 => "POLEZNAYA_DLINA_M",
			27 => "OBEM_ML",
			28 => "TSENA_ZA_M2",
			29 => "OBEM_M3",
			30 => "POKRYTIE",
			31 => "DLINA_M",
			32 => "TOLSHCHINA_MM_1",
			33 => "VES_KG_M2",
			34 => "PLOTNOST_KG_M2",
			35 => "UF_ZASHCHITA_MKR",
			36 => "VYSOTA_M",
			37 => "VES_LISTA_KG",
			38 => "DLINA_MM",
			39 => "DIAMETR_MM_1",
			40 => "RAZMERY_LISTA_M",
			41 => "OBEM_L",
			42 => "RAZMER",
			43 => "MAKSIMALNAYA_NAGRUZKA",
			44 => "SHAG_MM",
			45 => "SHIRINA_LEZVIYA_MM",
			46 => "VES_KG",
			47 => "SHIRINA_M",
			48 => "TROS_SHT",
		),
		"SHOW_MEASURE_WITH_RATIO" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SHOW_ARTICLE_SKU" => "N",
		"RESTART" => "N",
		"USE_LANGUAGE_GUESS" => "Y",
		"NO_WORD_LOGIC" => "Y",
		"SHOW_COUNTER_LIST" => "Y",
		"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"SECTIONS_LIST_ROOT_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SUBSECTION_PREVIEW_DESCRIPTION" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>