<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?global $SITE_THEME, $TEMPLATE_OPTIONS;?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.optimus", 
	"top_slider_banners", 
	array(
		"IBLOCK_TYPE" => "aspro_optimus_adv",
		"IBLOCK_ID" => "2",
		"TYPE_BANNERS_IBLOCK_ID" => "1",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "15",
		"SORT_BY1" => "TIMESTAMP_X",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "MARKER_ORD",
		),
		"CHECK_DATES" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"SITE_THEME" => $SITE_THEME,
		"BANNER_TYPE_THEME" => "TOP",
		"COMPONENT_TEMPLATE" => "top_slider_banners",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
