<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"catalog", 
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "Y",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"CATEGORY_0_TITLE" => GetMessage("CATEGORY_PRODUСTCS_SEARCH_NAME"),
		"CATEGORY_0" => array(
			0 => "iblock_1c_catalog",
		),
		"CATEGORY_0_iblock_aspro_optimus_catalog" => array(
			0 => "14",
		),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-searchs-input2",
		"CONTAINER_ID" => "title-search2",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"SHOW_ANOUNCE" => "N",
		"PREVIEW_TRUNCATE_LEN" => "50",
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "38",
		"PREVIEW_HEIGHT" => "38",
		"CONVERT_CURRENCY" => "N",
		"COMPONENT_TEMPLATE" => "catalog",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CATEGORY_0_iblock_1c_catalog" => array(
			0 => "16",
		)
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>