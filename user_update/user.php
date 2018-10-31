<?
global $USER;
if(!is_object($USER)) $USER=new CUser;
//exit;
//Обновление списка пользователей из HighLoad блока -> Удаление списка пользователей из HighLoad блока
$HLUsers = array();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');
$HLBlock = HL\HighloadBlockTable::getById(4)->fetch();
$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
$HLObjectClass = $HLObject->getDataClass();
$HLResultArray = $HLObjectClass::getList(array(
    'select' => array('*'),
));

$added = false;

while ($HLResult = $HLResultArray->fetch()) {
	
	$added = true;
	
	if(propuskEmail(trim($HLResult["UF_ELEKTRONNAYAPOCHT"])) === false) {
		
		$userSpecGroup = 0;
		if ($HLResult["UF_GRUPPANASAYTE"] == "КП(ФИЗ)") {
			$userSpecGroup = 9;
		} elseif ($HLResult["UF_GRUPPANASAYTE"] == "КП(ЮР)") {
			$userSpecGroup = 14;
		} elseif ($HLResult["UF_GRUPPANASAYTE"] == "СО(КМС)") {
			$userSpecGroup = 10;
		} elseif ($HLResult["UF_GRUPPANASAYTE"] == "СО(ПГС)") {
			$userSpecGroup = 12;
		} elseif ($HLResult["UF_GRUPPANASAYTE"] == "ТО") {
			$userSpecGroup = 11;
		}
		$HLUsers[$HLResult["UF_ELEKTRONNAYAPOCHT"]] = $HLResult;
		$HLUserID = $HLResult["ID"];
		$HLUserName = $HLResult["UF_NAME"];
		$HLUserINN = $HLResult["UF_INN"];
		$HLUserKPP = $HLResult["UF_KPP"];
		$HLUserPhone = $HLResult["UF_TELEFONKONTRAGENT"];
		$HLUserEmail = $HLResult["UF_ELEKTRONNAYAPOCHT"];
		$HLUserBonuses = $HLResult["UF_OSTATOKBONUSOV"];
		$HLUserGroup = $HLResult["UF_GRUPPANASAYTE"];
		$HLUserAccumulation = (float) preg_replace('/\s/', '',$HLResult["UF_OBSHCHIYOBOROT"]);
		$HLUserCGroups = trim($HLResult["UF_TSENOVYEGRUPPY"]);
		if($HLUserCGroups) {
			$HLUserCGroups = explode(";",$HLUserCGroups);
		}

		if($HLUserGroup=="КП(ФИЗ)" || $HLUserGroup=="КП(ЮР)"){
			if($HLUserAccumulation > 10000){
				$userSpecGroup = 15;
			}
		}

		//Обновление существующих пользователей
		$arrUsers = Bitrix\Main\UserTable::getList(array(
			'select' => array('ID', 'EMAIL', 'NAME', 'PERSONAL_PHONE','UF_INN','UF_KPP'),
			'filter' => array('EMAIL' => $HLUserEmail),
		));

		while ($arrUserData = $arrUsers->fetch()) {
			$userID = $arrUserData["ID"];
			if($userID != '1' || $userID != 1){

				$user = new CUser;

				$userUpdateFields = [
					"LOGIN" => $HLUserEmail,
					"EMAIL" => $HLUserEmail,
					"PERSONAL_PROFESSION" => $HLUserGroup,
					"UF_BONUSES" => $HLUserBonuses,
					"UF_ACCUMULATION" => $HLUserAccumulation,
					"UF_PRICE_GROUPS"=> $HLUserCGroups,
					"GROUP_ID" => array(2, 3, 4, 5, 6, $userSpecGroup),
			   ];
			   if(empty($arrUserData["NAME"])) 
					$userUpdateFields["NAME"] = $HLUserName;
			   if(empty($arrUserData["PERSONAL_PHONE"])) 
					$userUpdateFields["PERSONAL_PHONE"] = $HLUserPhone;
			   if(empty($arrUserData["UF_INN"])) 
					$userUpdateFields["UF_INN"] = $HLUserINN;
			   if(empty($arrUserData["UF_KPP"])) 
					$userUpdateFields["UF_KPP"] = $HLUserKPP;



				#    "NAME" => "",
				#    "SECOND_NAME" => " ",
				#   "LAST_NAME" => " ",
				$user->Update($userID, $userUpdateFields);

				/* ADD or UPDATE User budget */
				$arFields = Array("USER_ID" => $userID, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $HLUserBonuses);
				$account = CSaleUserAccount::GetByUserID($userID, "RUB");
				if(!$account) {
					$accountID = CSaleUserAccount::Add($arFields);
				} else {
					$accountID = CSaleUserAccount::Update($account['ID'],$arFields);
				}

			}
			$HLObjectClass::Delete($HLUserID);//Удаление списка существующих пользователей из HighLoad блока
		}

		$userAdd = new CUser;
		$password = \Bitrix\Main\Authentication\ApplicationPasswordTable::generatePassword();

		$userAddFields = array(
			"NAME" => $HLUserName,
			"SECOND_NAME" => " ",
			"LAST_NAME" => " ",
			"LOGIN" => $HLUserEmail,
			"EMAIL" => $HLUserEmail,
			"PERSONAL_PHONE" => $HLUserPhone,
			"PERSONAL_PROFESSION" => $HLUserGroup,
			"UF_BONUSES" => $HLUserBonuses,
			"UF_ACCUMULATION" => $HLUserAccumulation,
			"UF_PRICE_GROUPS"=> $HLUserCGroups,
			"UF_INN" => $HLUserINN,
			"UF_KPP" => $HLUserKPP,
			"GROUP_ID" => array(2, 3, 4, 5, 6, $userSpecGroup),
			"LID" => "ru",
			"ACTIVE" => "Y",
			"PASSWORD" => $password,
			"CONFIRM_PASSWORD" => $password,
		);
		$ID = $userAdd->Add($userAddFields);
		if (IntVal($ID) > 0) {
			$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока
			
			/* Отправка пиьсма с паролем */
			$arEventFields= array(
				"LOGIN" => $HLUserEmail,
				"NAME" => $HLUserName,
				"PASSWORD" => "Пароль: " . $password,
				"EMAIL" => $HLUserEmail,
				"SERVER_NAME" => "stroylogistika.ru",
				"USER_ID" => $ID,
				);
			$CV = CEvent::Send("USER_INFO_STRLOG", SITE_ID, $arEventFields, "N", 96,[],LANGUAGE_ID);

			/* ADD User budget */
			$arFields = Array("USER_ID" => $ID, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $HLUserBonuses);
			$accountID = CSaleUserAccount::Add($arFields);

		} else {
			$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока
		}
	} else {
		$HLObjectClass::Delete($HLResult["ID"]);
	}
}

function propuskEmail($value="")
{
	$emails = ['dima19681@ya.ru', 'lenysion@gmail.com', 'sgn_sse@mail.ru', 'yarik780@yandex.ru', 'lightworksa@gmail.com', 'podluzhnaya.a@mail.ru', 'viprobinzon@irk.ru', 'mir-krepega@bk.ru', 'krasnik_andrey@mail.ru', 'sergradionov@yandex.ru', 'krpm@mail.ru', 'avs367@mail.ru', 'boronov_baitog@rambler.ru', 'novichkov_vn@demetra.ru', 'potap.irk@yandex.ru', 'asmakssn@gmail.com', 'gutehexee@gmail.com', 'komarinochka@mail.ru', 'm.krilov2015@yandex.ru', 'sg.dm@mail.ru', 'pushkarev93@yandex.ru', 'bars.irk@mail.ru', '17dis04@list.ru', 'irk.sklad@khabtt.ru', '424460@mail.ru', 'kapai46v@gmail.com', 'taigarden@yandex.ru', 'missmo.store.info@gmail.com', 'che_lex7@mail.ru', 'mkredentser@yandex.ru', 'gnovik009@gmail.com', 'yeollihyeon@gmail.com', 'ya.kapai@ya.ru', 'paw1964@mail.ru', 'kernel_asv@yahoo.com', 'ma-prezental@mail.ru', 'kr-dennis@mail.ru', 'benedyuk23@gmail.com', 'asgard@mail.ru', 'ys.tsvetkov@gmail.com', 'subochev_vladim@mail.ru', 'ne1drug@gmail.com', 'aviamexanik@icloud.com', 'palchikov.58@mail.ru', 'ddenisoff@mail.ru', 'py9aa@inbox.ru', 'fetisov-60@list.ru', 'bookkeeper2003@mail.ru', 'vlad38ru@gmail.com', 'vashal@mail.ru', 'biryuk_stanislav@mail.ru', 'profi1204@mail.ru', '1274423@gmail.com', 'berkut210777@yandex.ru', 'konovlyoha@mail.ru', 'ovchinnikov.im@yandex.ru', 'basheff.oleg@yandex.ru', 'decor@mirgos.ru', 'kuzminskaya@baikalsea.com', 'vaswet1003@gmail.com', 'apmbox@gmail.com', 'mymoding@mail.ru', 'yriy_konchakov@mail.ru', 'teplyakov89@gmail.com', '907-900@mail.ru', 'wit-vent@mail.ru', 'aviamexanik77@gmail.com', 'dukirill@mail.ru', 'ale13264@yandex.ru', 'bur4nov@yandex.ru', 'arteomz@yandex.ru', 'dimakirk@gmail.com', 'tvv.85@ya.ru', 'iznu@irk.ru', 'angarsk@inbox.ru', 'fartusova.katya@mail.ru', 'alex_pin@mail.ru', 'vafint@bk.ru', 'st-expo@mail.ru', 'kobreus@gmail.com', 'adigo777@list.ru', 'v.gilev@groupstp.ru', 'zav1964@yandex.ru', 'belovkirill@yandex.ru', 'strlogist@yandex.ru', 'sarma201@mail.ru', 'yasksergej@yandex.ru', 'ya.tna77@yandex.ru', 'griha888@gmail.com', 'a663903@yandex.ru', 'miks.st@mail.ru', 'vlastin-vm@yandex.ru', 'anik_60@mail.ru', 'kuliasov.v@yandex.ru', 'comradnikitin@yandex.ru', 'nalofree@gmail.com', '149@irk.ru', 'iltadi@mail.ru', 'papa@strlog.ru'];
	
	return in_array($value,$emails);
}
?>