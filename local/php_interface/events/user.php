<?
	use Bitrix\Main\Loader; 
	use Bitrix\Highloadblock as HL; 
	use Bitrix\Main\Entity;
	
	AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler"); 

function OnAfterUserRegisterHandler(&$arFields)
{
	global $_SESSION;
	$_SESSION['NU']['PASSWORD'] = $arFields["PASSWORD"];
	define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/log-useraddorder.txt");
	AddMessage2Log("OnAfterUserRegister  - : " . serialize($arFields));
	$profile_exists = false;
	 
	$userID = $arFields['USER_ID'];
	
	$db_sales = CSaleOrderUserProps::GetList(
		array("DATE_UPDATE" => "DESC"),
		array("USER_ID" => $userID)
	);

	if ($ar_sales = $db_sales->Fetch())
	{
	   $profile_exists = true;
	}
		
	if($profile_exists == false) {
	  //создаём профиль
      //PERSON_TYPE_ID - идентификатор типа плательщика, для которого создаётся профиль
		$PERSON_TYPE_ID = 1;
		if(trim($arFields['PERSONAL_PROFESSION']) == "КП(ФИЗ)") $PERSON_TYPE_ID = 1;
		if(trim($arFields['PERSONAL_PROFESSION']) == "КП(ЮР)") $PERSON_TYPE_ID = 2;

		$userGroups = \CUser::GetUserGroup($userID);

		$arGroups[] = $PERSON_TYPE_ID == 2 ? 14 : 9;
		$userGroups = array_merge($userGroups, $arGroups);
		\CUser::SetUserGroup($userID, $userGroups);

		//if($PERSON_TYPE_ID == 2){
		//}

      $arProfileFields = array(
         "NAME" => "Профиль покупателя (".$arFields['EMAIL'].')',
         "USER_ID" => $arFields['USER_ID'],
         "PERSON_TYPE_ID" => $PERSON_TYPE_ID
      );

      $PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields);
      
      //если профиль создан
      if ($PROFILE_ID)
      {
         //формируем массив свойств
         $PROPS=Array(
         array(
               "USER_PROPS_ID" => $PROFILE_ID,
               "ORDER_PROPS_ID" => 3,
               "NAME" => "Телефон",
               "VALUE" => $arFields['PERSONAL_PHONE']
            ),
         array(
               "USER_PROPS_ID" => $PROFILE_ID,
               "ORDER_PROPS_ID" => 1,
               "NAME" => "Ф.И.О.",
               "VALUE" => ($arFields['LAST_NAME']?$arFields['LAST_NAME'] . " " :"") . $arFields['NAME'] . ($arFields['SECOND_NAME']?" " . $arFields['SECOND_NAME']:"")
            )
         );

		  if($PERSON_TYPE_ID == 2){
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 8,
				   "NAME" => "Название компании",
				   "VALUE" => $arFields['NAME']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 10,
				   "NAME" => "ИНН",
				   "VALUE" => $arFields['UF_INN']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 11,
				   "NAME" => "КПП",
				   "VALUE" => $arFields['UF_KPP']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 19,
				   "NAME" => "Адрес доставки",
				   "VALUE" => " "
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 8,
				   "NAME" => "Название компании",
				   "VALUE" => $arFields['UF_COMPANY']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 24,
				   "NAME" => "Адрес доставки",
				   "VALUE" => $arFields['UF_BANK_NAME']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 25,
				   "NAME" => "Адрес доставки",
				   "VALUE" => $arFields['UF_BANK_ACCOUNT']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 26,
				   "NAME" => "Адрес доставки",
				   "VALUE" => $arFields['UF_BANK_COR_ACCOUNT']
					];
			$PROPS[] = [
				   "USER_PROPS_ID" => $PROFILE_ID,
				   "ORDER_PROPS_ID" => 27,
				   "NAME" => "Адрес доставки",
				   "VALUE" => $arFields['UF_BANK_BIK']
					];
		  }
         //добавляем значения свойств к созданному ранее профилю
         foreach ($PROPS as $prop)
            CSaleOrderUserPropsValue::Add($prop);
      }
	}
}

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
function OnBeforeUserRegisterHandler(&$args)
{
	global $_SESSION;
	$_SESSION['NU']['PASSWORD'] = $args["PASSWORD"];

	define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/log-useraddorder.txt");
	AddMessage2Log("OnBeforeUserRegister  - : " . serialize($args));
	
	if(isset($args['LOGIN']) && $args["PERSONAL_PROFESSION"]=="КП(ЮР)"){

		$userFullName = trim($args['UF_LEGAL_FORM']);

		if(empty($userFullName)){
			$GLOBALS['APPLICATION']->ThrowException('Не выбрано значение поля "Правовая форма организации"'. $args['UF_LEGAL_FORM']); 
  			return false;
		} 

		Loader::includeModule("highloadblock"); 
				
		$hlbl = 5; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
		$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
		
		$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
		$entity_data_class = $entity->getDataClass(); 
		
		$rsData = $entity_data_class::getList(array(
		   "select" => array("UF_FULL_NAME"),
		   "order" => array("ID" => "ASC"),
		   "filter" => array("ID"=>$args['UF_LEGAL_FORM'])  // Задаем параметры фильтра выборки
		));
		
		if($arData = $rsData->Fetch()){
			
			$exp = explode('"',$args['UF_COMPANY']);
			if(isset($exp[1])) $args['UF_COMPANY'] = $exp[1];
		
			$userFullName = $arData['UF_FULL_NAME'] . ' "'.$args['UF_COMPANY'].'"';
		}

		$args['UF_COMPANY'] = $userFullName;
	}

	return true; 
}

AddEventHandler("main", "OnBeforeUserAdd", Array("UserModifyHandler", "OnBeforeUserAddHandler"));
class UserModifyHandler
{
    // создаем обработчик события "OnBeforeUserAdd"
    function OnBeforeUserAddHandler(&$arFields)
    {
		if ($_POST["PERSON_TYPE"] == 1 && $_POST["ORDER_PROP_1"] ) {
			$fio = trim($_POST["ORDER_PROP_1"]);
			$fio = preg_replace("#[\s]+#siu"," ", $fio);
			$exp = explode(" ", $fio);
			$arFields["SECOND_NAME"] = isset($exp[2]) ? $exp[2] : "";
		}
		return true;
    }
}

/* Группа пользователя */
function getGroupUser()
{
	global $USER;
	$userID = $USER->GetID();
	$userGroups = \CUser::GetUserGroup($userID);

	foreach($userGroups as $gr){
		$gr = (int)$gr;
		if($gr === 9 || $gr === 14) {
			return "КП";
		}
		if($gr === 10 || $gr === 12 || $gr === 15) {
			return "СО";
		}
		if($gr === 11) {
			return "ТО";
		}
	}
	
	return "КП";
}

function getFilePrice($uGr)
{
	if(file_exists(rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/bitrix/px_files/Прайс_$uGr.XLS")){
		return rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/bitrix/px_files/Прайс_$uGr.XLS";
	}
	
	return null;
}

?>