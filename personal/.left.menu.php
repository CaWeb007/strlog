<?
use Bitrix\Main\Loader;
use Caweb\Main\Sale\Helper;
Loader::includeModule('caweb.main');
global $checkBonus;
$checkBonus = Helper::getInstance()->checkBonusAccess();
$aMenuLinks = Array(
	Array(
		"Мой кабинет", 
		"/personal/index.php", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Текущие заказы", 
		"/personal/orders/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Бонусы", 
		"/personal/account/", 
		Array(), 
		Array(),
        '$GLOBALS["checkBonus"]'
	),
	Array(
		"Личные данные", 
		"/personal/private/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Сменить пароль", 
		"/personal/change-password/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"История заказов", 
		"/personal/orders/?filter_history=Y", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Профили заказов", 
		"/personal/profiles/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Корзина", 
		"/basket/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Подписки", 
		"/personal/subscribe/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Выйти", 
		"?logout=yes&login=yes", 
		Array(), 
		Array("class"=>"exit"), 
		"\$GLOBALS[\"USER\"]->isAuthorized()" 
	)
);
?>