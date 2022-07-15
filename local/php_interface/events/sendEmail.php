<?
// Смена емайла для групп ТО и СО
addEventHandler('sale', 'OnOrderNewSendEmail', "OnOrderNewSendEmailHandler");
function OnOrderNewSendEmailHandler($newOrderId, &$eventName, &$arFields)
{
	if($eventName == "SALE_NEW_ORDER" || $eventName == "NEW_ONE_CLICK_BUY") {
		global $USER;
		$userID = $USER->GetID();
		$userGroups = \CUser::GetUserGroup($userID);
	
		$arFields['BCC'] = 'zakaz@strlog.ru';
		
		foreach($userGroups as $gr){
			if((int)$gr === 10 || (int)$gr === 12) {
				$arFields['BCC'] = 'co@strlog.ru';
				break;
			}
			if((int)$gr === 11) {
				$arFields['BCC'] = 'to@strlog.ru';
				break;
			}
            if((int)$gr === 14) {
                $arFields['BCC'] = 'corp@strlog.ru';
                break;
            }
		}
		
		$arOrder = CSaleOrder::GetByID($newOrderId);
		
		//-- получаем телефоны и адрес
		$order_props = CSaleOrderPropsValue::GetOrderProps($newOrderId);
		$phone="";
		$index = "";
		$country_name = "";
		$city_name = "";
		$address = "";
		$name = "";
		$otchestvo = "";
		$familiya = "";
		$PAY_BONUS = 0;
		
		while ($arProps = $order_props->Fetch())
		{
			if ($arProps["CODE"] == "PHONE")
			{
			   $phone = htmlspecialchars($arProps["VALUE"]);
			}
			if ($arProps["CODE"] == "LOCATION")
			{
				$arLocs = CSaleLocation::GetByID($arProps["VALUE"]);
				$country_name =  $arLocs["COUNTRY_NAME_ORIG"];
				$city_name = $arLocs["CITY_NAME_ORIG"];
			}

			if ($arProps["CODE"] == "INDEX")
			{
			  $index = $arProps["VALUE"];
			}

			if ($arProps["CODE"] == "ADDRESS")
			{
			  $address = $arProps["VALUE"];
			}
			
			// custom
			if($arProps["CODE"] == "SECOND_NAME"){
				$otchestvo = $arProps["VALUE"];
			}
			if($arProps["CODE"] == "LAST_NAME"){
				$familiya = $arProps["VALUE"];
			}
			if($arProps["CODE"] == "FIO"){
				$name = $arProps["VALUE"];
			}
			if($arProps["CODE"] == "PAY_BONUS"){
				$PAY_BONUS = (float)$arProps["VALUE"];
			}
		} 
	
		define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/log-sendemail.txt");
			
		$arFields["PAY_BONUS"] = CurrencyFormat(0, "RUB");
		$arFields["PRICE_FINAL"] = $arFields["PRICE"];
		
		AddMessage2Log("PRICE_FINAL 0 - " . $arFields["PRICE_FINAL"] . " : " . serialize($arFields));
		
		if($PAY_BONUS > 0){
			$p = str_replace([" ",","],["","."],$arFields["PRICE"]);
			$p = (float)$p;
			$arFields["PAY_BONUS"] = CurrencyFormat($PAY_BONUS, "RUB");
			$arFields["PRICE_FINAL"] = CurrencyFormat($p-$PAY_BONUS, "RUB");
		}
		
		AddMessage2Log("PRICE_FINAL 1 - " . $arFields["PRICE_FINAL"] . " : " . serialize($arFields));
		
		$full_address = $index.", ".$country_name."-".$city_name.", ".$address;

		//-- получаем название службы доставки
		$arDeliv = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
		$delivery_name = "";
		if ($arDeliv)
		{
			$delivery_name = $arDeliv["NAME"];
		}

		//-- получаем название платежной системы
		$arPaySystem = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"]);
		$pay_system_name = "";
		if ($arPaySystem)
		{
			$pay_system_name = $arPaySystem["NAME"];
		}

		//-- добавляем новые поля в массив результатов
		/*$arFields["ORDER_USER"] = "";
		$arFields["ORDER_USER"] = ($familiya?$familiya:"");
		$arFields["ORDER_USER"] .= ($arFields["ORDER_USER"]?" ":"") . $name;
		$arFields["ORDER_USER"] .= ($otchestvo?" ":"") . $otchestvo;*/
		
		$arFields["ORDER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
		$arFields["PHONE"] =  $phone;
		$arFields["DELIVERY_NAME"] =  $delivery_name;
		$arFields["PAY_SYSTEM_NAME"] =  $pay_system_name;
		$arFields["FULL_ADDRESS"] = $full_address;
	}

	return true;
}

/* Смотри Файл functions/user.php*
addEventHandler('main', 'OnAfterUserRegister', "OnAfterUserRegisterHandler");

function OnAfterUserRegisterHandler(&$arFields)
{
}*/

addEventHandler('main', 'OnBeforeEventAdd', "OnBeforeEventAddHandler");

function OnBeforeEventAddHandler(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
{
	if($event == "USER_INFO") {
		global $GLOBAL;
		$event = "USER_INFO_STRLOG";
		$message_id = 95;
		$arFields['PASSWORD'] = "Пароль: " . $GLOBAL['NUP']['VALUE'];
	}
	
	if($event == "NEW_ONE_CLICK_BUY") {
		global $USER;
		$userID = $USER->GetID();
		$userGroups = \CUser::GetUserGroup($userID);
	
		$arFields['DEFAULT_EMAIL_FROM'] = 'zakaz@strlog.ru';
		
		foreach($userGroups as $gr){
			if((int)$gr === 10 || (int)$gr === 12) {
				$arFields['DEFAULT_EMAIL_FROM'] = 'co@strlog.ru';
				break;
			}
			if((int)$gr === 11) {
				$arFields['DEFAULT_EMAIL_FROM'] = 'to@strlog.ru';
				break;
			}
		}
	}

}

?>