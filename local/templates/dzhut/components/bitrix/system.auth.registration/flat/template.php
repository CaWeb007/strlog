<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//one css for all system.auth.* forms
// $APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
			<?$APPLICATION->IncludeComponent(
				"uni:main.register",
				"register_page",
				Array(
					"AUTH" => "Y",
					"REQUIRED_FIELDS" => array("EMAIL", "NAME", "LAST_NAME", "PERSONAL_BIRTHDAY", "PERSONAL_PHONE"),
					"SET_TITLE" => "Y",
					"SHOW_FIELDS" => array("EMAIL", "NAME", "LAST_NAME", "PERSONAL_GENDER", "PERSONAL_BIRTHDAY", "PERSONAL_PHONE"),
					"SUCCESS_PAGE" => "",
					"USER_PROPERTY" => array(),
					"USER_PROPERTY_NAME" => "",
					"USE_BACKURL" => "Y"
				)
			);?>
			<div class="bx-authform-link-container">
					<a href="/auth/?login=yes" rel="nofollow">Авторизация</a>
				</div>