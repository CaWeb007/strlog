<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
	global $USER; 
	$userID = $USER->GetID();
	
	$userGroups = \CUser::GetUserGroup($userID);
	
	$intersect = array_intersect([10,11,12,14,15], $userGroups);
		
	if($intersect){
		$SET_DEFAULT_PERSON_TYPE = 2;
		$SET_DEFAULT_PROPERTIES = ["CONTACT_PERSON","PHONE","EMAIL","COMPANY","INN","KPP","COMMENT"];
		$SET_DEFAULT_REQUIRED = ["CONTACT_PERSON","FIO","PHONE","EMAIL","INN"];
	} else {
		$SET_DEFAULT_PERSON_TYPE = 1;
		$SET_DEFAULT_PROPERTIES = ["FIO","PHONE","EMAIL","COMMENT"];
		$SET_DEFAULT_REQUIRED = ["FIO","PHONE","EMAIL"];
	}
	#COption::SetOptionString('aspro.optimus', 'ONECLICKBUY_PERSON_TYPE', $SET_DEFAULT_PERSON_TYPE, false, SITE_ID);
?>
<?$APPLICATION->IncludeComponent("aspro:oneclickbuy.optimus", "shop", array(
	"BUY_ALL_BASKET" => "Y",
	"CACHE_TYPE" => "Y",
	"CACHE_TIME" => "3600000",
	"CACHE_GROUPS" => "N",
	"SHOW_DELIVERY_NOTE" => COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_SHOW_DELIVERY_NOTE', 'N', SITE_ID),
	"PROPERTIES" => $SET_DEFAULT_PROPERTIES,#(strlen($tmp = COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_PROPERTIES', 'FIO,PHONE,EMAIL,COMMENT', SITE_ID)) ? explode(',', $tmp) : array()),
	"REQUIRED" => $SET_DEFAULT_REQUIRED,#(strlen($tmp = COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_REQUIRED_PROPERTIES', 'FIO,PHONE', SITE_ID)) ? explode(',', $tmp) : array()),
	"DEFAULT_PERSON_TYPE" => COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_PERSON_TYPE', '1', SITE_ID),
	"DEFAULT_DELIVERY" => COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_DELIVERY', '2', SITE_ID),
	"DEFAULT_PAYMENT" => COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_PAYMENT', '1', SITE_ID),
	"DEFAULT_CURRENCY" => COption::GetOptionString('aspro.optimus', 'ONECLICKBUY_CURRENCY', 'RUB', SITE_ID),
	),
	false
);?>