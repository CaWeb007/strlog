<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?><?$APPLICATION->SetTitle("Регистрация");?> <?
//AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");
function OnAfterUserAddHandler(&$arFields){
    if($_REQUEST['select_user_group'] == 'user_group_fiz_kp'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 9;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
        $user = new CUser;
        $updateField = array("PERSONAL_PROFESSION" => "КП");
        $user->Update($arFields["ID"], $updateField);
    }elseif($_REQUEST['select_user_group'] == 'user_group_fiz_kms'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 10;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
        $user = new CUser;
        $updateField = array("PERSONAL_PROFESSION" => "КМС");
        $user->Update($arFields["ID"], $updateField);
    }elseif($_REQUEST['select_user_group'] == 'user_group_ur_to'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 11;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
        $user = new CUser;
        $updateField = array("PERSONAL_PROFESSION" => "ТО");
        $user->Update($arFields["ID"], $updateField);
    }elseif($_REQUEST['select_user_group'] == 'user_group_ur_pgs'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 12;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
        $user = new CUser;
        $updateField = array("PERSONAL_PROFESSION" => "ПГС");
        $user->Update($arFields["ID"], $updateField);
    }elseif($_REQUEST['select_user_group'] == 'user_group_ur_kms'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 13;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
    }elseif($_REQUEST['select_user_group'] == 'user_group_ur_pr'){
        $arGroups = CUser::GetUserGroup($arFields["ID"]);
        $arGroups[] = 14;
        CUser::SetUserGroup($arFields["ID"], $arGroups);
        $user = new CUser;
        $updateField = array("PERSONAL_PROFESSION" => "КМС");
        $user->Update($arFields["ID"], $updateField);
    }
}
?> <?if(!$USER->IsAuthorized()):?>
<div class="registration-tabs-wrapper">
	<ul class="registration-tabs-list">
		<li class="registration-tab registration-fiz-tab<?if(!isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) || (isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) && $_REQUEST['REGISTER']['PERSONAL_PROFESSION']=="КП(ФИЗ)")):?> registration-tab-active<?endif?>">Физлицо</li>
		<li class="registration-tab registration-ur-tab<?if(isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) && $_REQUEST['REGISTER']['PERSONAL_PROFESSION']=="КП(ЮР)"):?> registration-tab-active<?endif?>">Юрлицо</li>
	</ul>
</div>
<div class="registration-fiz-face"<?if(isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) && $_REQUEST['REGISTER']['PERSONAL_PROFESSION']=="КП(ЮР)"):?> style="display:none"<?else:?> style="display:block"<?endif?>>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"main",
	Array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array("EMAIL","LAST_NAME","NAME","PERSONAL_PHONE"),
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array("LAST_NAME","NAME","SECOND_NAME","EMAIL","PERSONAL_PHONE","PERSONAL_PROFESSION"),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => array(),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?>
</div>
<div class="registration-ur-face"<?if(!isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) || (isset($_REQUEST['REGISTER']['PERSONAL_PROFESSION']) && $_REQUEST['REGISTER']['PERSONAL_PROFESSION']=="КП(ФИЗ)")):?> style="display:none"<?else:?> style="display:block"<?endif?>>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"main-urface",
	Array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array("EMAIL","NAME","PERSONAL_PHONE"),
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array("NAME","EMAIL","PERSONAL_PHONE","PERSONAL_PROFESSION"),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => array("UF_LEGAL_FORM","UF_COMPANY","UF_INN",'UF_KPP'),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?>
</div>
    <?$_REQUEST["REGISTER[LOGIN]"] = $_REQUEST["REGISTER[EMAIL]"];?>
<?elseif(!empty( $_REQUEST["backurl"] )):?>
<?LocalRedirect( $_REQUEST["backurl"] );?>
<?else:?>
<?LocalRedirect(SITE_DIR.'personal/');?>
<?endif;?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>