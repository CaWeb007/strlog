<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "ajax_popup", array(
	"REGISTER_URL" => SITE_DIR."auth/registration/",
	"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/",
	"PROFILE_URL" => SITE_DIR."personal/",
	"SHOW_ERRORS" => "Y"
	)
);?>