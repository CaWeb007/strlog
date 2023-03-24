<? use Bitrix\Main\Page\Asset;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($GET["debug"] == "y"){
    error_reporting(E_ERROR | E_PARSE);
}
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $TEMPLATE_OPTIONS, $arSite;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
    <head>
        <title><?$APPLICATION->ShowTitle()?></title>
        <?$APPLICATION->ShowMeta("viewport");?>
        <?$APPLICATION->ShowMeta("HandheldFriendly");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
        <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
		<?$APPLICATION->ShowHead();?>
        <?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>

        <?/*Подключение vue.js*end*/?>
		<link rel="stylesheet" type="text/css" href="<?= SITE_TEMPLATE_PATH ?>/tooltipster/dist/css/tooltipster.bundle.min.css" />
		<link rel="stylesheet" type="text/css" href="<?= SITE_TEMPLATE_PATH ?>/tooltipster/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css">
		<script type="text/javascript" src="<?= SITE_TEMPLATE_PATH ?>/tooltipster/dist/js/tooltipster.bundle.min.js"></script>
        <?if(CModule::IncludeModule("aspro.optimus")) {COptimus::Start(SITE_ID);}?>

        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">

        <!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
        <!--<link href="<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset='latin,cyrillic'" rel='stylesheet' type='text/css' />-->
        <?
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/custom2.css');
        ?>

<?/*Костыли*start*показ меню каталога только на главной странице*/
	//var_dump("<pre>",$TEMPLATE_OPTIONS);?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NP3Q8H5');</script>
<!-- End Google Tag Manager -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(37983465, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/37983465" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?if(COptimus::IsCatalogPage()):?>
    <?global $TEMPLATE_OPTIONS;?>
    <?if($TEMPLATE_OPTIONS['MENU_POSITION_MAIN']['VALUES'][0]['NAME'] == 'да'):?>
        <?$TEMPLATE_OPTIONS['MENU_POSITION_MAIN']['VALUES'][0]['NAME'] = 'нет'?>
        <?$TEMPLATE_OPTIONS['MENU_POSITION_MAIN']['VALUES'][1]['NAME'] = 'да'?>
        <?$TEMPLATE_OPTIONS['MENU_POSITION_MAIN']['CURRENT_VALUE'] = 'HIDE'?>
    <?endif;?>
<?endif;?>
<?if(COptimus::IsMainPage()):?>
    <style>
        .dropdown .has-childs .dropdown .menu_item, .menu_top_block .dropdown .dropdown .menu_item{
            display: block !important;
            height: auto !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }
    </style>
<?else:?>
    <style>
        .menu_top_block .dropdown .dropdown .dropdown .menu_item{
            display: block !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }
    </style>
<?endif;?>
<?/*Костыли*end*показ меню каталога только на главной странице*/?>  


</head>
<body id="main">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NP3Q8H5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div id="panel"><?$APPLICATION->ShowPanel();?></div>
    <div class="onload"></div>
<?if(!CModule::IncludeModule("aspro.optimus")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
<?$APPLICATION->IncludeComponent("aspro:theme.optimus", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
<?COptimus::SetJSOptions();?>
<div class="wrapper <?=(COptimus::getCurrentPageClass());?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> with_fast_view <?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?> banner_auto">
    <div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
        <?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]=="NORMAL"){?>
            <div class="top-h-row">
                <div class="wrapper_inner">
                    <div class="flex-line">
                        <div class="flex-item">
                            <div class="flex-item__menu">
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                    array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "PATH" => SITE_DIR."include/menu/menu.strlog_top_content_multilevel.php",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "standard.php"
                                    ),
                                    false
                                );?>
                            </div>
                        </div>
                        <div class="flex-item">
                            <div class="flex-item__phone">
                                <span class="icons fa fa-phone"></span>
                                <span class="phone_text">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                            array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR."include/phone.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "standard.php"
                                            ),
                                            false
                                        );?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="flex-item">
                            <div class="flex-item__top-auth">
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                    array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "PATH" => SITE_DIR."include/topest_page/auth.top.php",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "standard.php"
                                    ),
                                    false
                                );?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?}?>
        <header id="header">
            <div class="top_data_wrapper">
                <div class="wrapper_inner top_data">
                    <div class="top_br"></div>
                    <div class="middle-h-row">
                            <div class="logo_wrapp">
								 <?php if(COptimus::IsMainPage() && 1==2) : ?>
								 <div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.optimus', 'NO_LOGO_BG', 'N'));?>">
                                    <?COptimus::ShowLogo();?>
                                </div>
                                <?php else: ?>
                                <div class="catalog_menu menu_<?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?>">
                                    <div style="z-index:11;background: none;">
                                        <div class="wrapper_middle_menu wrap_menu" style="background: none;">
                                            <div class="catalog_menu_ext">
                                                <?$APPLICATION->IncludeComponent(
                                                    "bitrix:main.include",
                                                    ".default",
                                                    array(
                                                        "COMPONENT_TEMPLATE" => ".default",
                                                        "PATH" => SITE_DIR."include/menu/menu.catalog.php",
                                                        "AREA_FILE_SHOW" => "file",
                                                        "AREA_FILE_SUFFIX" => "",
                                                        "AREA_FILE_RECURSIVE" => "Y",
                                                        "EDIT_TEMPLATE" => "standard.php",
                                                        "COMPOSITE_FRAME_MODE" => "A",
                                                        "COMPOSITE_FRAME_TYPE" => "AUTO"
                                                    ),
                                                    false
                                                );?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <!--
                                <div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.optimus', 'NO_LOGO_BG', 'N'));?>">
                                    <?COptimus::ShowLogo();?>
                                </div>
                                -->
                            </div>
                            <!--
                            <div class="text_wrapp">
                                <div class="slogan">
                                    <?/*$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "PATH" => SITE_DIR."include/top_page/slogan.php",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "standard.php"
                                    ),
                                        false,
                                        array(
                                            "ACTIVE_COMPONENT" => "N"
                                        )
                                    );*/?>
                                </div>
                            </div>
                            -->
                            <div  class="center_block">
                                <div class="search">
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                        array(
                                            "COMPONENT_TEMPLATE" => ".default",
                                            "PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php"
                                        ),
                                        false
                                    );?>
                                </div>
                            </div>
                            <div class="basket_wrapp">
                                <?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "NORMAL"){?>
                                    <div class="wrapp_all_icons">
                                        <div class="header-compare-block icon_block iblock" id="compare_line" >
                                            <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                array(
                                                    "COMPONENT_TEMPLATE" => ".default",
                                                    "PATH" => SITE_DIR."include/top_page/catalog.compare.list.compare_top.php",
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "",
                                                    "AREA_FILE_RECURSIVE" => "Y",
                                                    "EDIT_TEMPLATE" => "standard.php"
                                                ),
                                                false
                                            );?>
                                        </div>
                                        <div class="header-cart" id="basket_line">
                                            <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                array(
                                                    "COMPONENT_TEMPLATE" => ".default",
                                                    "PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "",
                                                    "AREA_FILE_RECURSIVE" => "Y",
                                                    "EDIT_TEMPLATE" => "standard.php"
                                                ),
                                                false
                                            );?>
                                        </div>
                                    </div>
                                <?}else{?>
                                    <div class="header-cart fly" id="basket_line">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                            array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "standard.php"
                                            ),
                                            false
                                        );?>
                                    </div>
                                    <div class="middle_phone">
                                        <div class="phones">
                                            <span class="phone_wrap">
                                                <span class="phone">
                                                    <span class="icons fa fa-phone"></span>
                                                    <span class="phone_text">
                                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                            array(
                                                                "COMPONENT_TEMPLATE" => ".default",
                                                                "PATH" => SITE_DIR."include/phone.php",
                                                                "AREA_FILE_SHOW" => "file",
                                                                "AREA_FILE_SUFFIX" => "",
                                                                "AREA_FILE_RECURSIVE" => "Y",
                                                                "EDIT_TEMPLATE" => "standard.php"
                                                            ),
                                                            false
                                                        );?>
                                                    </span>
                                                </span>
                                                <span class="order_wrap_btn">
                                                    <span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                <?}?>
                                <div class="clearfix"></div>
                            </div>

                    </div>
                </div>
            </div><!--/top_data_wrapper-->

            <?/*
            <div class="catalog_menu menu_<?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?>">
                <div class="wrapper_inner" style="z-index:11;">
                    <div class="wrapper_middle_menu wrap_menu">
                        <ul class="menu adaptive">
                            <li class="menu_opener">
                                <div class="text">
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                        array(
                                            "COMPONENT_TEMPLATE" => ".default",
                                            "PATH" => SITE_DIR."include/menu/menu.mobile.title.php",
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php"
                                        ),
                                        false
                                    );?>
                                </div>
                            </li>
                        </ul>
                        <!--Top menu-->
                        <div class="catalog_menu_ext">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                ".default",
                                array(
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "PATH" => SITE_DIR."include/menu/menu.catalog.php",
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "",
                                    "AREA_FILE_RECURSIVE" => "Y",
                                    "EDIT_TEMPLATE" => "standard.php",
                                    "COMPOSITE_FRAME_MODE" => "A",
                                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                                ),
                                false
                            );?>
                        </div>
                        <?if(!COptimus::IsMainPage()):?>
                        <div class="container">
                                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "optimus", array(
                                    "START_FROM" => "0",
                                    "PATH" => "",
                                    "SITE_ID" => "-",
                                    "SHOW_SUBSECTIONS" => "N"
                                    ),
                                    false
                                );?>
                        </div>
                        <?endif;?>
                        <!--/Top menu-->
                        <div class="inc_menu top-content-menu">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                array(
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "PATH" => SITE_DIR."include/menu/menu.top_content_multilevel.php",
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "",
                                    "AREA_FILE_RECURSIVE" => "Y",
                                    "EDIT_TEMPLATE" => "standard.php"
                                ),
                                false
                            );?>
                        </div>
                    </div>
                </div>
            </div>
            */?>
        </header>
    </div>
    <div class="wraps" id="content">
<div class="wrapper_inner <?=(COptimus::IsMainPage() ? "front" : "");?> <?=((COptimus::IsOrderPage() || COptimus::IsBasketPage()) ? "wide_page" : "");?>">
<?
$exp = explode("/",trim($APPLICATION->sDirPath,"/"));
$iSDetailPage = (COptimus::IsCatalogPage() && count($exp) > 2 && !in_array("filter",$exp) && !in_array("apply",$exp));
?>
<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage() && !$iSDetailPage){?>
    <div class="left_block">
        <?/*Статичное меню*start*JS скрипт выпадающего меню находится bitrix/menu/left_front_catalog/template.php*/?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
        <?/*Статичное меню*end*/?>

        <?$APPLICATION->ShowViewContent('left_menu');?>

        <?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH" => SITE_DIR."include/left_block/comp_banners_left.php",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_TEMPLATE" => "standard.php",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_subscribe.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_news.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_news_articles.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/actions_list_info.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
    </div>
    <div class="right_block">
<?}?>
    <div class="middle">
<?if(!COptimus::IsMainPage()):?>
    <div class="container">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "optimus", array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => "-",
        "SHOW_SUBSECTIONS" => "N"
    ),
        false
    );?>
    <?$APPLICATION->ShowViewContent('section_bnr_content');?>
			<!--title_content-->
			<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
			<!--end-title_content-->
<?endif;?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>