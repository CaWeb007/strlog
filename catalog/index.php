<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?><?

?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_FILTER_CATALOG" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"ALSO_BUY_ELEMENT_COUNT" => "5",
		"ALSO_BUY_MIN_BUYES" => "2",
		"ASK_FORM_ID" => "2",
		"BASKET_URL" => "/basket/",
		"BIG_DATA_RCM_TYPE" => "any_similar",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"COMPARE_FIELD_CODE" => array(
			0 => "NAME",
			1 => "TAGS",
			2 => "SORT",
			3 => "PREVIEW_PICTURE",
			4 => "",
		),
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
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
		"COMPARE_POSITION" => "top left",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_PROPERTY_CODE" => array(
			0 => "_1",
			1 => "BRAND",
			2 => "METROV_V_UPAKOVKE",
			3 => "MOSHCHNOST_VT",
			4 => "RAZMERY_VES",
			5 => "STEKLOVOLOKNISTAYA_OSNOVA_G_M2",
			6 => "TSENA_ZA_M2",
			7 => "SAP",
			8 => "DLINA_MM_1",
			9 => "KONSTRUKTSIYA",
			10 => "RULONOV_V_UPAKOVKE",
			11 => "TIP_BITUMNOGO_VYAZHUSHCHEGO",
			12 => "MATERIAL",
			13 => "METROV_V_RULONE",
			14 => "NIZHNEE_POKRYTIE",
			15 => "DUGI",
			16 => "TEPLOSTOYKOST_S",
			17 => "CML2_ARTICLE",
			18 => "CML2_BASE_UNIT",
			19 => "vote_count",
			20 => "CML2_MANUFACTURER",
			21 => "RASSTOYANIE_MEZHDU_DUGAMI",
			22 => "rating",
			23 => "CML2_TRAITS",
			24 => "SOSTAV",
			25 => "CML2_TAXES",
			26 => "vote_sum",
			27 => "CML2_ATTRIBUTES",
			28 => "CML2_BAR_CODE",
			29 => "STRINGERY",
			30 => "TIP_POSYPKI",
			31 => "OKRASKA",
			32 => "POKRYTIE",
			33 => "SOEDINENIE_ELEMENTOV",
			34 => "KREPEZH_DLYA_SBORKI_FURNITURA",
			35 => "KREPEZH_DLYA_POLIKARBONATA",
			36 => "INSTRUKTSIYA_PO_SBORKE",
			37 => "TOLSHCHINA_MM_1",
			38 => "GRUPPA_TOVAROV",
			39 => "GARANTIYA",
			40 => "TORTSY",
			41 => "VYSOTA_MM",
			42 => "KOLICHESTVO_GNEZD_SHT",
			43 => "RAZMER_MM",
			44 => "DIAMETR_MM_1",
			45 => "PROIZVODITEL_1",
			46 => "RAZMER_SM",
			47 => "DLINA_M",
			48 => "BREND",
			49 => "PLOTNOST_KG_M2_1",
			50 => "KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K",
			51 => "TOLSHCHINA_MM",
			52 => "VES_KG_M2",
			53 => "NAGRUZKA_T",
			54 => "DIAMETR_OBOYMY_MM",
			55 => "UF_ZASHCHITA_MKR",
			56 => "VES_LISTA_KG",
			57 => "VYSOTA_OBOYMY_MM",
			58 => "ZASHCHITNYY_SLOY_MM",
			59 => "RAZMERY_LISTA_M",
			60 => "KOMPLEKTATSIYA",
			61 => "MAKSIMALNAYA_NAGRUZKA",
			62 => "TEMPERATURA_NACHALA_OTKRYVANIYA_POLNOGO_ZAKRYTIYA_",
			63 => "VES_KG",
			64 => "TROS_SHT",
			65 => "TALREP_SHT",
			66 => "KARABIN_SHT",
			67 => "ZAZHIM_M3_SHT",
			68 => "UGOLOK_25KH25_MM_SHT",
			69 => "SAMOREZ_4_2KH13_SHT",
			70 => "SORT",
			71 => "MINIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			72 => "SHIRINA_MM",
			73 => "MAKSIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			74 => "TIP",
			75 => "SHIRINA_RULONA_MM",
			76 => "TEMPERATURA_NACHALA_ZAKRYVANIYA_POLNOGO_OTKRYTIYA",
			77 => "TEMPERATURA_EKSPLUATATSII",
			78 => "TSVET",
			79 => "KLASS_PROCHNOSTI",
			80 => "OBEM_ML",
			81 => "POLEZNAYA_DLINA_M",
			82 => "PROIZVODITEL",
			83 => "BEST",
			84 => "VYVOD",
			85 => "MESTO_KREPLENIYA",
			86 => "OBEM_M3",
			87 => "SERIYA",
			88 => "SHIRINA_MM_1",
			89 => "RAZMER_M",
			90 => "ACTION",
			91 => "NEW",
			92 => "MAIN",
			93 => "VYSOTA_M",
			94 => "DLINA_MM",
			95 => "KOLICHESTVO_SOT",
			96 => "PLOTNOST_KG_M2",
			97 => "DIAMETR_MM",
			98 => "RAZMER",
			99 => "SYRE",
			100 => "KOLICHESTVO_V_UPAKOVKE_SHT",
			101 => "SHAG_MM",
			102 => "DLINA_NOZHKI_MM",
			103 => "SHIRINA_LEZVIYA_MM",
			104 => "PLOTNOST_G_M2",
			105 => "MOROZOSTOYKOST",
			106 => "RAZRESHENNYY_OPLATY_BONUSAMI",
			107 => "_POROGA_NACHISLENIYA_BONUSOV",
			108 => "NALICHIE",
			109 => "OBEM_L",
			110 => "POD_ZAKAZ_CHEREZ",
			111 => "SHIRINA_M",
			112 => "HIT",
			113 => "179",
			114 => "PLOSHCHAD_M2",
			115 => "ID_TOVARA_NA_SAYTE",
			116 => "NAZVANIE_DLYA_LENDINGA",
			117 => "HIT2",
			118 => "PROP_2033",
			119 => "COLOR_REF2",
			120 => "PROP_159",
			121 => "PROP_2052",
			122 => "PROP_2027",
			123 => "PROP_2053",
			124 => "PROP_2083",
			125 => "PROP_2049",
			126 => "PROP_2026",
			127 => "PROP_2044",
			128 => "PROP_162",
			129 => "PROP_2065",
			130 => "PROP_2054",
			131 => "PROP_2017",
			132 => "PROP_2055",
			133 => "PROP_2069",
			134 => "PROP_2062",
			135 => "PROP_2061",
			136 => "",
		),
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "main",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"DEFAULT_COUNT" => "1",
		"DEFAULT_LIST_TEMPLATE" => "table",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"DETAIL_ASSOCIATED_TITLE" => "Аксессуары",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"DETAIL_EXPANDABLES_TITLE" => "Похожие товары",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => "",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => "",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "DETAIL_PAGE_URL",
			4 => "",
		),
		"DETAIL_OFFERS_LIMIT" => "0",
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "M2",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "",
		),
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "METROV_V_UPAKOVKE",
			2 => "MOSHCHNOST_VT",
			3 => "NEKONDITSIYA",
			4 => "RAZMERY_VES",
			5 => "STEKLOVOLOKNISTAYA_OSNOVA_G_M2",
			6 => "DLINA_MM_1",
			7 => "KONSTRUKTSIYA",
			8 => "RULONOV_V_UPAKOVKE",
			9 => "TIP_BITUMNOGO_VYAZHUSHCHEGO",
			10 => "MATERIAL",
			11 => "METROV_V_RULONE",
			12 => "NIZHNEE_POKRYTIE",
			13 => "DUGI",
			14 => "TEPLOSTOYKOST_S",
			15 => "CML2_ARTICLE",
			16 => "GRUZOPODEMNOST_KG",
			17 => "RASSTOYANIE_MEZHDU_DUGAMI",
			18 => "SOSTAV",
			19 => "CML2_ATTRIBUTES",
			20 => "STRINGERY",
			21 => "TIP_POSYPKI",
			22 => "OKRASKA",
			23 => "POKRYTIE",
			24 => "SOEDINENIE_ELEMENTOV",
			25 => "KREPEZH_DLYA_SBORKI_FURNITURA",
			26 => "KREPEZH_DLYA_POLIKARBONATA",
			27 => "INSTRUKTSIYA_PO_SBORKE",
			28 => "TOLSHCHINA_MM_1",
			29 => "GRUPPA_TOVAROV",
			30 => "GARANTIYA",
			31 => "TORTSY",
			32 => "VYSOTA_MM",
			33 => "KOLICHESTVO_GNEZD_SHT",
			34 => "RAZMER_MM",
			35 => "DIAMETR_MM_1",
			36 => "PROIZVODITEL_1",
			37 => "RAZMER_SM",
			38 => "DLINA_M",
			39 => "PLOTNOST_KG_M2_1",
			40 => "KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K",
			41 => "TOLSHCHINA_MM",
			42 => "VES_KG_M2",
			43 => "NAGRUZKA_T",
			44 => "DIAMETR_OBOYMY_MM",
			45 => "UF_ZASHCHITA_MKR",
			46 => "VES_LISTA_KG",
			47 => "VYSOTA_OBOYMY_MM",
			48 => "ZASHCHITNYY_SLOY_MM",
			49 => "RAZMERY_LISTA_M",
			50 => "KOMPLEKTATSIYA",
			51 => "MAKSIMALNAYA_NAGRUZKA",
			52 => "TEMPERATURA_NACHALA_OTKRYVANIYA_POLNOGO_ZAKRYTIYA_",
			53 => "VES_KG",
			54 => "TROS_SHT",
			55 => "TALREP_SHT",
			56 => "KARABIN_SHT",
			57 => "ZAZHIM_M3_SHT",
			58 => "UGOLOK_25KH25_MM_SHT",
			59 => "SAMOREZ_4_2KH13_SHT",
			60 => "SORT",
			61 => "MINIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			62 => "SHIRINA_MM",
			63 => "MAKSIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			64 => "TIP",
			65 => "SHIRINA_RULONA_MM",
			66 => "TEMPERATURA_NACHALA_ZAKRYVANIYA_POLNOGO_OTKRYTIYA",
			67 => "TEMPERATURA_EKSPLUATATSII",
			68 => "TSVET",
			69 => "KLASS_PROCHNOSTI",
			70 => "OBEM_ML",
			71 => "POLEZNAYA_DLINA_M",
			72 => "PROIZVODITEL",
			73 => "VYVOD",
			74 => "MESTO_KREPLENIYA",
			75 => "OBEM_M3",
			76 => "SERIYA",
			77 => "SHIRINA_MM_1",
			78 => "RAZMER_M",
			79 => "PLOSHCHAD",
			80 => "VYSOTA_M",
			81 => "DLINA_MM",
			82 => "KOLICHESTVO_SOT",
			83 => "PLOTNOST_KG_M2",
			84 => "DIAMETR_MM",
			85 => "RAZMER",
			86 => "SYRE",
			87 => "KOLICHESTVO_V_UPAKOVKE_SHT",
			88 => "SHAG_MM",
			89 => "DLINA_NOZHKI_MM",
			90 => "SHIRINA_LEZVIYA_MM",
			91 => "PLOTNOST_G_M2",
			92 => "MOROZOSTOYKOST",
			93 => "OBEM_L",
			94 => "SHIRINA_M",
			95 => "ZV_1",
			96 => "VIDEO_YOUTUBE",
			97 => "PROP_2033",
			98 => "COLOR_REF2",
			99 => "PROP_159",
			100 => "PROP_2052",
			101 => "PROP_2027",
			102 => "PROP_2053",
			103 => "PROP_2083",
			104 => "PROP_2049",
			105 => "PROP_2026",
			106 => "PROP_2044",
			107 => "PROP_162",
			108 => "PROP_2065",
			109 => "PROP_2054",
			110 => "PROP_2017",
			111 => "PROP_2055",
			112 => "PROP_2069",
			113 => "PROP_2062",
			114 => "PROP_2061",
			115 => "RECOMMEND",
			116 => "STOCK",
			117 => "VIDEO",
			118 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_SHOW_VIEWED" => "Y",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_ELEMENT_COUNT" => "N",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"DISPLAY_ELEMENT_SLIDER" => "10",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "catalog_QUANTITY",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",//changePriceID(),
			2 => "",
		),
		"FILTER_HIDE_ON_MOBILE" => "N",
		"FILTER_NAME" => "OPTIMUS_SMART_FILTER",
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
		"FILTER_PRICE_CODE" => changePriceID(),
		"FILTER_PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FORUM_ID" => "1",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"HIDE_NOT_AVAILABLE" => "L",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "16",
		"IBLOCK_STOCK_ID" => "12",
		"IBLOCK_TYPE" => "1c_catalog",
		"INCLUDE_SUBSECTIONS" => "N",
		"INSTANT_RELOAD" => "N",
		"LABEL_PROP" => "",
		"LANDING_IBLOCK_ID" => "",
		"LANDING_PAGE_ELEMENT_COUNT" => "20",
		"LANDING_SECTION_COUNT" => "8",
		"LANDING_TITLE" => "",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "4",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID" => "16",
		"LINK_IBLOCK_TYPE" => "1c_catalog",
		"LINK_PROPERTY_SID" => "BRAND",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_META_KEYWORDS" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "CML2_LINK",
			2 => "DETAIL_PAGE_URL",
			3 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "M2",
		),
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"LIST_PROPERTY_CODE" => array(
			0 => "METROV_V_UPAKOVKE",
			1 => "MOSHCHNOST_VT",
			2 => "RAZMERY_VES",
			3 => "STEKLOVOLOKNISTAYA_OSNOVA_G_M2",
			4 => "TSENA_ZA_KG",
			5 => "TSENA_ZA_M2",
			6 => "DLINA_MM_1",
			7 => "KONSTRUKTSIYA",
			8 => "RULONOV_V_UPAKOVKE",
			9 => "TIP_BITUMNOGO_VYAZHUSHCHEGO",
			10 => "MATERIAL",
			11 => "METROV_V_RULONE",
			12 => "NIZHNEE_POKRYTIE",
			13 => "DUGI",
			14 => "TEPLOSTOYKOST_S",
			15 => "RASSTOYANIE_MEZHDU_DUGAMI",
			16 => "SOSTAV",
			17 => "STRINGERY",
			18 => "TIP_POSYPKI",
			19 => "OKRASKA",
			20 => "POKRYTIE",
			21 => "SOEDINENIE_ELEMENTOV",
			22 => "KREPEZH_DLYA_SBORKI_FURNITURA",
			23 => "KREPEZH_DLYA_POLIKARBONATA",
			24 => "INSTRUKTSIYA_PO_SBORKE",
			25 => "TOLSHCHINA_MM_1",
			26 => "GARANTIYA",
			27 => "TORTSY",
			28 => "VYSOTA_MM",
			29 => "KOLICHESTVO_GNEZD_SHT",
			30 => "RAZMER_MM",
			31 => "DIAMETR_MM_1",
			32 => "RAZMER_SM",
			33 => "DLINA_M",
			34 => "PLOTNOST_KG_M2_1",
			35 => "KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K",
			36 => "TOLSHCHINA_MM",
			37 => "VES_KG_M2",
			38 => "NAGRUZKA_T",
			39 => "DIAMETR_OBOYMY_MM",
			40 => "UF_ZASHCHITA_MKR",
			41 => "VES_LISTA_KG",
			42 => "VYSOTA_OBOYMY_MM",
			43 => "ZASHCHITNYY_SLOY_MM",
			44 => "RAZMERY_LISTA_M",
			45 => "KOMPLEKTATSIYA",
			46 => "MAKSIMALNAYA_NAGRUZKA",
			47 => "TEMPERATURA_NACHALA_OTKRYVANIYA_POLNOGO_ZAKRYTIYA_",
			48 => "VES_KG",
			49 => "TROS_SHT",
			50 => "TALREP_SHT",
			51 => "KARABIN_SHT",
			52 => "ZAZHIM_M3_SHT",
			53 => "UGOLOK_25KH25_MM_SHT",
			54 => "SAMOREZ_4_2KH13_SHT",
			55 => "SORT",
			56 => "MINIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			57 => "SHIRINA_MM",
			58 => "MAKSIMALNAYA_DLINA_TERMOPRIVODA_MEZHDU_OPOR_MM",
			59 => "TIP",
			60 => "SHIRINA_RULONA_MM",
			61 => "TEMPERATURA_NACHALA_ZAKRYVANIYA_POLNOGO_OTKRYTIYA",
			62 => "TEMPERATURA_EKSPLUATATSII",
			63 => "TSVET",
			64 => "KLASS_PROCHNOSTI",
			65 => "OBEM_ML",
			66 => "POLEZNAYA_DLINA_M",
			67 => "MESTO_KREPLENIYA",
			68 => "OBEM_M3",
			69 => "SHIRINA_MM_1",
			70 => "RAZMER_M",
			71 => "PLOSHCHAD",
			72 => "VYSOTA_M",
			73 => "DLINA_MM",
			74 => "KOLICHESTVO_SOT",
			75 => "PLOTNOST_KG_M2",
			76 => "DIAMETR_MM",
			77 => "RAZMER",
			78 => "SYRE",
			79 => "KOLICHESTVO_V_UPAKOVKE_SHT",
			80 => "SHAG_MM",
			81 => "DLINA_NOZHKI_MM",
			82 => "SHIRINA_LEZVIYA_MM",
			83 => "PLOTNOST_G_M2",
			84 => "MOROZOSTOYKOST",
			85 => "NALICHIE",
			86 => "OBEM_L",
			87 => "SHIRINA_M",
			88 => "179",
			89 => "PLOSHCHAD_M2",
			90 => "PROP_2033",
			91 => "COLOR_REF2",
			92 => "PROP_159",
			93 => "PROP_2052",
			94 => "PROP_2027",
			95 => "PROP_2053",
			96 => "PROP_2083",
			97 => "PROP_2049",
			98 => "PROP_2026",
			99 => "PROP_2044",
			100 => "PROP_162",
			101 => "PROP_2065",
			102 => "PROP_2054",
			103 => "PROP_2017",
			104 => "PROP_2055",
			105 => "PROP_2069",
			106 => "PROP_2062",
			107 => "PROP_2061",
			108 => "CML2_LINK",
			109 => "",
		),
		"LIST_PROPERTY_CODE_MOBILE" => "",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"LOAD_ON_SCROLL" => "N",
		"MAIN_TITLE" => "Наличие на складах",
		"MAX_AMOUNT" => "20",
		"MESSAGES_PER_PAGE" => "10",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_COMMENTS_TAB" => "Комментарии",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_PRICE_RANGES_TITLE" => "Цены",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"MIN_AMOUNT" => "10",
		"NO_WORD_LOGIC" => "Y",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "CML2_ATTRIBUTES",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "CATALOG_QUANTITY",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_HIDE_NAME_PROPS" => "N",
		"OFFER_TREE_PROPS" => array(
			'M2'
		),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "20",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"POST_FIRST_MESSAGE" => "N",
		"PRICE_CODE" => changePriceID(),
		"USE_WORD_EXPRESSION" => "Y",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_DISPLAY_MODE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTIES_DISPLAY_LOCATION" => "DESCRIPTION",
		"PROPERTIES_DISPLAY_TYPE" => "TABLE",
		"RESTART" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"SALE_STIKER" => "SALE_TEXT",
		"SEARCH_CHECK_DATES" => "Y",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "Y",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SECTIONS_LIST_ROOT_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_DISPLAY_PROPERTY" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
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
		"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
		"SECTION_TOP_DEPTH" => "2",
		"SEF_FOLDER" => "/catalog/",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ADDITIONAL_TAB" => "N",
		"SHOW_ARTICLE_SKU" => "N",
		"SHOW_ASK_BLOCK" => "Y",
		"SHOW_BRAND_PICTURE" => "Y",
		"SHOW_COUNTER_LIST" => "Y",
		"SHOW_DEACTIVATED" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_DISCOUNT_TIME" => "N",
		"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
		"SHOW_EMPTY_STORE" => "N",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"SHOW_HINTS" => "Y",
		"SHOW_KIT_PARTS" => "Y",
		"SHOW_KIT_PARTS_PRICES" => "Y",
		"SHOW_LINK_TO_FORUM" => "Y",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_MEASURE" => "Y",
		"SHOW_MEASURE_WITH_RATIO" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_ONE_CLICK_BUY" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_QUANTITY" => "Y",
		"SHOW_QUANTITY_COUNT" => "Y",
		"SHOW_RATING" => "Y",
		"SHOW_SECTION_LIST_PICTURES" => "Y",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SECTION_SIBLINGS" => "Y",
		"SHOW_TOP_ELEMENTS" => "Y",
		"SHOW_UNABLE_SKU_PROPS" => "Y",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"SKU_DETAIL_ID" => "oid",
		"SORT_BUTTONS" => array(
			0 => "POPULARITY",
			1 => "PRICE",
			2 => "QUANTITY",
		),
		"SORT_PRICES" => "КП",
		"STORES" => array(
			0 => "49",
			1 => "",
		),
		"STORE_PATH" => "/contacts/stores/#store_id#/",
		"SUBSECTION_PREVIEW_DESCRIPTION" => "Y",
		"TEMPLATE_THEME" => "blue",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ELEMENT_COUNT" => "8",
		"TOP_ELEMENT_SORT_FIELD" => "shows",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_LINE_ELEMENT_COUNT" => "4",
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "10",
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_VIEW_MODE" => "SECTION",
		"URL_TEMPLATES_READ" => "",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"USE_ALSO_BUY" => "N",
		"USE_BIG_DATA" => "Y",
		"USE_CAPTCHA" => "Y",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"USE_COMPARE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_FILTER" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_LANGUAGE_GUESS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_MIN_AMOUNT" => "Y",
		"USE_ONLY_MAX_AMOUNT" => "Y",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "Y",
		"USE_RATING" => "Y",
		"USE_REVIEW" => "Y",
		"USE_SALE_BESTSELLERS" => "Y",
		"USE_STORE" => "N",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"VIEWED_BLOCK_TITLE" => "Ранее вы смотрели",
		"VIEWED_ELEMENT_COUNT" => "20",
		"CACHE_NOTES" => "",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>