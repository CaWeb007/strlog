<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($GET["debug"] == "y"){
    error_reporting(E_ERROR | E_PARSE);
}
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $TEMPLATE_OPTIONS, $arSite;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" xmlns="http://www.w3.org/1999/xhtml" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
    <head>
        <title><?$APPLICATION->ShowTitle()?></title>
        <?$APPLICATION->ShowMeta("viewport");?>
        <?$APPLICATION->ShowMeta("HandheldFriendly");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
        <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
        <?$APPLICATION->ShowHead();?>
        <?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
        <?/*Подключение vue.js*start*/?>
        <script src="https://unpkg.com/vue"></script>
        <?/*Подключение vue.js*end*/?>
        <?if(CModule::IncludeModule("aspro.optimus")) {COptimus::Start(SITE_ID);}?>

        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">

        <!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
        <!--<link href="<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset='latin,cyrillic'" rel='stylesheet' type='text/css' />-->
    </head>

<?/*Костыли*start*показ меню каталога только на главной странице*/?>
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
<body id="main">
    <div id="panel"><?$APPLICATION->ShowPanel();?></div>
    <div class="onload"></div>
<?if(!CModule::IncludeModule("aspro.optimus")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
<?$APPLICATION->IncludeComponent("aspro:theme.optimus", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
<?COptimus::SetJSOptions();?>
<div class="wrapper <?=(COptimus::getCurrentPageClass());?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> <?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?> banner_auto">
    <div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
        <?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]=="NORMAL"){?>
            <div class="top-h-row">
                <div class="wrapper_inner our-projects-wrap">
                    <div class="top_inner our-projects-wrapper-anchor">
                        <div id="our-projects" class="our-projects-wrapper">
                            <a class="strlog-city-link" href="/company/contacts/">Иркутск</a>
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
                            <span class="our-projects" v-on:click="dropProjects">Наши проекты<img v-bind:class="{scaled:isScaled}" src="/images/project-arrow.png" /></span>
                            <div class="our-projects-dropdown-wrapper" v-bind:class="{dropped: isDropped}">
                                <ul>
                                    <li>
                                        <a href="https://polzairk.ru/" target="_blank">
                                            <div class="our-projects-image-wrapper">
                                                <img src="/images/project_icon_1.png" alt="" title="" />
                                            </div>
                                            <span>
                                                Здесь вы можете купить готовую теплицу из поликарбоната по низкой цене от производителя!
                                                Собственный завод теплиц "Польза" был основан в 2011 году. Мы рады предложить вам теплицы и парники любой длины.
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <div class="our-projects-image-wrapper">
                                                <img src="/images/project_icon_2.png" alt="" title="" />
                                            </div>
                                            <span>
                                                Здесь вы можете купить качественный межвенцовый утеплитель (джут) по низкой цене от производителя!
                                                Собственный завод межвенцового утеплителя «ФЭЛТ» в Иркутске. Быстро отгрузим и доставим!
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <div class="our-projects-image-wrapper">
                                                <img src="/images/project_icon_3.png" alt="" title="" />
                                            </div>
                                            <span>
                                                Компания «Периметр» - компания по производству, продаже и установке ворот, дверей, заборов и шлагбаумов для частных и промышленных объектов!
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <div class="our-projects-image-wrapper">
                                                <img src="/images/project_icon_4.png" alt="" title="" />
                                            </div>
                                            <span>
                                                «Sotex» - завод сотового поликарбоната, основан в 2009 году, лидер рынка региона по производству и продажам листов сотового поликарбоната.
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="h-user-block" id="personal_block">
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
                        <div class="phones">
                            <div class="phone_block">
                                <span class="phone_wrap">
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
                                <!--
                                <span class="order_wrap_btn">
                                    <span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
                                </span>
                                -->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?}?>
        <header id="header">
            <div class="top_data_wrapper">
                <div class="wrapper_inner top_data">
                    <div class="top_br"></div>
                    <table class="middle-h-row">
                        <tr>
                            <td class="logo_wrapp">
                                <?php if(COptimus::IsMainPage()) : ?>
                                <div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.optimus', 'NO_LOGO_BG', 'N'));?>">
                                    <?COptimus::ShowLogo();?>
                                </div>
                                <?php else: ?>
                                <div class="catalog_menu menu_<?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?>">
                                    <div class="wrapper_inner" style="z-index:11;background: none;">
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
                            </td>
                            <!--
                            <td class="text_wrapp">
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
                            </td>
                            -->
                            <td  class="center_block">
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
                            </td>
                            <td class="basket_wrapp">
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
                            </td>
                        </tr>
                    </table>
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
<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()){?>
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

        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_banners_left.php",
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
        <?if(CModule::IncludeModule("iblock")):?>
            <?$IblockID = 26;?>
            <?$File = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$IblockID,"CODE"=>"prays-list-stroylogistika"), false, array(), array("ID", "NAME", "PROPERTY_ATT_DOWNLOAD_PRICE"));?>
            <?if($arFile = $File->GetNext()):?>
                <?$fileInfo = CFile::GetFileArray($arFile['PROPERTY_ATT_DOWNLOAD_PRICE_VALUE']);?>
                <div class="left_price">
                    <a target="_blanc" title="Прайс лист" href="<?=$fileInfo['SRC']?>">
                        <span class="left_price_title">Прайс лист </span>
                        <span class="left_price_date"> (<?=date('d.m.Y')?>)<!--(07.05.2018 12:35)--></span>
                    </a>
                </div>
            <? endif;?>
        <?endif;?>
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