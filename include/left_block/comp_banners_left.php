<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?global $SITE_THEME, $TEMPLATE_OPTIONS;?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.optimus", 
	"left_banner", 
	array(
		"IBLOCK_TYPE" => "aspro_optimus_adv",
		"IBLOCK_ID" => "2",
		"TYPE_BANNERS_IBLOCK_ID" => "2",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "1",
		"SORT_BY1" => "rand",
		"SORT_ORDER1" => "rand",
		"SORT_BY2" => "",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "BUTTON2CLASS",
			1 => "BUTTON1CLASS",
			2 => "MARKER",
			3 => "TEXT_POSITION",
			4 => "TARGETS",
			5 => "TEXTCOLOR",
			6 => "NAV_COLOR",
			7 => "URL_STRING",
			8 => "BUTTON1TEXT",
			9 => "BUTTON1LINK",
			10 => "BUTTON2TEXT",
			11 => "BUTTON2LINK",
			12 => "",
		),
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"SITE_THEME" => $SITE_THEME,
		"BANNER_TYPE_THEME" => "LEFT",
		"COMPONENT_TEMPLATE" => "left_banner",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
