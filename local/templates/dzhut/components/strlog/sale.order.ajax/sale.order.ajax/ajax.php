<?//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION, $LOGIN, $PASSWORD, $MESSAGE, $REQUEST_URL;
// SMS
$LOGIN = 'Strlog';
$PASSWORD = md5(123456);
$MESSAGE = 'Ваш код ';
//$REQUEST_URL = 'https://mcommunicator.ru/m2m/m2m_api.asmx/SendMessage';//Изменен в связи с письмом от МТС от 24.04.2018
$REQUEST_URL = 'https://api.mcommunicator.ru/m2m/m2m_api.asmx/SendMessage';
$user_id = $USER->GetID();
// CODE_CONFIRM
function SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION){
	
	$CODE = rand(1000,10000);

	$HashCode = md5($CODE);

	$MESSAGE .= $CODE;
	
	$POSTFIELDS = 'msid='.$MSID.'&message='.$MESSAGE.'&naming=STRLOG&login='.$LOGIN.'&password='.$PASSWORD.'';
	
	$curl  = curl_init();
	curl_setopt($curl,CURLOPT_URL,$REQUEST_URL);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_HEADER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl,CURLOPT_REFERER,'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$POSTFIELDS);
	
	$result = curl_exec($curl);
	$curlInfo = curl_getinfo($curl);
	curl_close($curl);
	if($curlInfo['http_code'] != 500){

		$Data = array("confirm"=>"Y","code"=>$code);
		echo $DataJSON = json_encode($Data);
		
		$APPLICATION->set_cookie('CODE_CONFIRM',$HashCode,time()+60);
		
	}else{
		echo json_encode(array("send_sms" => "N"));
	}
	
}
// CODE_CONFIRM TEST
function codeConfirm($APPLICATION){
			
		$code = rand(1000,10000);
		$hashCode = md5($code);
		$Data = array("confirm"=>"Y","code"=>$code);
		echo $DataJSON = json_encode($Data);
		$APPLICATION->set_cookie('CODE_CONFIRM',$hashCode,time()+60);
}

if(CModule::IncludeModule("main")){
	// CHECK USER
	if(isset($_POST['userEmail']) && isset($_POST['userPhone'])){
		$email = htmlspecialchars(trim($_POST['userEmail']));
		$phone = htmlspecialchars(trim($_POST['userPhone']));
		$MSID = preg_replace('/[^0-9]/','',$phone); // оставляем только числа
		$order = array('sort' => 'asc');
		$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть

		if(!$USER->IsAuthorized()) {
			$arFilter = array(
				"EMAIL" => $email
			);
			$arFilter2 = array(
				"PERSONAL_PHONE" => $phone
			);
		} else {
			$arFilter = array(
				"EMAIL" => $email,
				"!ID" => $user_id
			);
			$arFilter2 = array(
				"PERSONAL_PHONE" => $phone,
				"!ID" => $user_id
			);
		}

		// создадим 2 экземпляра CUser для проверки телефона и email
		$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("EMAIL","PERSONAL_PHONE")));
		$rsUsers2 = CUser::GetList($order, $tmp, $arFilter2,array("FIELDS"=>array("EMAIL","PERSONAL_PHONE")));

		$email_flag = true;
		$phone_flag = true;

		if(!empty($rsUsers->NavNext(true, "f_"))) {
			// выполняем на полное соответствие (не включение!). Возвращаем ошибку только если в базе есть в точности такой же email
			if($email == $f_EMAIL) {
				$email_flag = false;				
			}
		}

		if(!empty($rsUsers2->NavNext(true, "f_"))) {
			// выполняем на полное соответствие (не включение!). Возвращаем ошибку только если в базе есть в точности такой же телефон
			if($phone == $f_PERSONAL_PHONE) {
				$phone_flag = false;
			}
		}
		
		if (!$email_flag && !$phone_flag) {
			$Data = array("email"=>$f_EMAIL,"phone"=>$f_PERSONAL_PHONE);
			echo $DataJSON = json_encode($Data);
		} else if (!$email_flag) {
			$Data = array("email"=>$f_EMAIL);
			echo $DataJSON = json_encode($Data);
		} else if (!$phone_flag) {
			$Data = array("phone"=>$f_PERSONAL_PHONE);
			echo $DataJSON = json_encode($Data);
		}else{
			//codeConfirm($APPLICATION);
			SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION);
		}
	}

	/* проверим email */
	if (isset($_POST['check_email'])) {
		$email = htmlspecialchars(trim($_POST['userEmail']));
		$order = array('sort' => 'asc');
		$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
		if(!$USER->IsAuthorized()) {
			$arFilter = array(
				"EMAIL" => $email
			);
		} else {
			$arFilter = array(
				"EMAIL" => $email,
				"!ID" => $user_id
			);
		}

		$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("EMAIL")));
		if(!empty($rsUsers->NavNext(true, "f_"))){
			
			if($email == $f_EMAIL) {
				$Data = array("success" => "N", "email"=>$f_EMAIL);
				echo $DataJSON = json_encode($Data);				
			} else {
				$Data = array("success" => "Y");
				echo $DataJSON = json_encode($Data);
			} 

		} else {
			$fields = Array(	  
				"EMAIL" => $email,
			  );
			// $USER->Update($user_id, $fields);
			$Data = array("success" => "Y");
			echo $DataJSON = json_encode($Data);
		}
	}

	// CHECK CODE
	if(isset($_POST['Code']) && isset($_POST['userPhone'])){

			$arFilter = array(
				"PERSONAL_PHONE" => $_POST['userPhone'],
			);

			// необходимо сделат проверку существования номера телефона, исключая данного пользователя
			if ($USER->IsAuthorized()) {
				$arFilter = array(
					"!ID" => $user_id,
					"PERSONAL_PHONE" => $_POST['userPhone'],
				);
			};

			$order = array('sort' => 'asc');
				$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть		

			$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("PERSONAL_PHONE")));
			
			if(!empty($rsUsers->NavNext(true, "f_"))){
				$Data = array("user"=>"Y","phone"=>$f_PERSONAL_);
				echo $DataJSON = json_encode($Data);
				exit();
			}
		
			$code = md5($_POST['Code']);
			
			if($APPLICATION->get_cookie('CODE_CONFIRM') == $code){
				$Data = array("code_confirm"=>"Y");
				echo $DataJSON = json_encode($Data);
			}else{
				$Data = array("code_confirm"=>"N");
				echo $DataJSON = json_encode($Data);
			}
	}

	// CONFIRM CODE
	if(isset($_POST['Reconfirm']) && isset($_POST['userPhone'])){
		//codeConfirm($APPLICATION);
		$phone = htmlspecialchars(trim($_POST['userPhone']));
		$MSID = preg_replace('/[^0-9]/','',$phone); // оставляем только числа

		// NEW CHECK_USER_PHONE
		$arFilter = array(
			"PERSONAL_PHONE" => $phone,
		);

		// необходимо сделат проверку существования номера телефона, исключая данного пользователя
		if ($USER->IsAuthorized()) {
			$arFilter = array(
				"!ID" => $user_id,
				"PERSONAL_PHONE" => $phone,
			);
		};

		$order = array('sort' => 'asc');
		$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть		
		$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("PERSONAL_PHONE")));
		
		if(!empty($rsUsers->NavNext(true, "f_"))){
			$Data = array("user"=>"Y","phone"=>$f_PERSONAL_PHONE);
			echo $DataJSON = json_encode($Data);
		}else{
			SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION);	
		}	
		exit();		
	}	
	

	
}
?>