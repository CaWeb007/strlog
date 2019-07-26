<?
IncludeModuleLangFile(__FILE__);

$aMenu = array(
    "parent_menu" => "global_menu_store",
    "sort" => 110,
    "text" => GetMessage("RBS_CREDIT_SERVICE_MODULE_NAME"),
    "icon" => "rbs_credit_menu_icon",
    "page_icon" => "rbs_credit_page_icon",
    "items_id" => "rbs_credit",
    "url" => "/bitrix/admin/rbs_credit_orders_list.php",
    "items" => array(
        array(
            "text" => GetMessage("RBS_CREDIT_SERVICE_ORDERS"),
            "url" => "/bitrix/admin/rbs_credit_orders_list.php",
            // "more_url" => array('/bitrix/admin/rbs_credit_order_edit.php')
        ),
        array(
            "text" => GetMessage("RBS_CREDIT_SERVICE_SETINGS"),
            "url" => "/bitrix/admin/settings.php?lang=". LANGUAGE_ID ."&mid=rbs.credit",
        )
    ),
);
return $aMenu;