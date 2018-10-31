<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?
if($USER->IsAdmin() && 1 > 2){
	require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");
	$csvFile = new CCSVData('R', true);
	$csvFile->LoadFile($_SERVER['DOCUMENT_ROOT']."/upload/export_users.csv");
	$csvFile->SetDelimiter(';');
	
	$userAdd = new CUser;
	while ($row = $csvFile->Fetch()) {
		//var_dump($row);break;
			$userAddFields = array(
				"LID" => "ru",
				"ACTIVE" => "Y",
				"PASSWORD" => "01012018",
				"CONFIRM_PASSWORD" => "01012018",
	
				'ID' => $row[0],
				'LOGIN' => $row[2],
				'ACTIVE' => $row[5],
				'NAME' => $row[6],
				'LAST_NAME' => $row[7],
				'EMAIL' => $row[8],
				"GROUP_ID" => array(2, 3, 4, 5, 6, 9),
				'PERSONAL_GENDER' => $row[10],
				'PERSONAL_PHONE' => $row[11],
				'PERSONAL_BIRTHDAY' => $row[12],
				'UF_BONUS' => $row[14],
				'UF_BONUS_PARTNER' => $row[15],
				'UF_CONFIRM_PHONE' => $row[16],
				'UF_LEGAL_FORM' => 1
			);
		$ID = $userAdd->Add($userAddFields);
		
		if(intval($ID) > 0){
			echo " Пользователь ".$row[6]." с ИД: ".$ID." успешно добавлен"."<br>";
		} else {
			echo " Ошибка добавления пользователя ".$row[6].": " . $userAdd->LAST_ERROR ."<br>";
		}
	
		global $DB;
		
		$strSql = "UPDATE b_user SET `password` = '".$row[3]."', "
								  ." `checkword` = '".$row[4]."', "
								  ." `checkword_time` = '".$row[13]."', "
								  ." `date_register` = '".$row[9]."', "
								  ." `timestamp_x` = '".$row[1]."', "
								  ." `id` = '".$row[0]."', "
								  ." `PERSONAL_PROFESSION` = 'КП(ФИЗ)' "
								  ." WHERE `login` = '".$row[2]."';";
		$res = $DB->Query($strSql, false);
	
		if($res){
			echo " Пароль пользователя ".$row[6]." с ИД: ".$ID." успешно обновлен"."<br>";
		} else {
			echo " Ошибка обновления пользователя ".$row[6]." с ИД: ".$ID.""."<br>";
		}
	
	
	}
}
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>