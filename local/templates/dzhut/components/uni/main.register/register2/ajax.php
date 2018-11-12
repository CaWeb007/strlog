<?//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

// CHEKC USER PHONE
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/sms.php')){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/sms.php');
}

// CUSTOM REGISTRATION ON API BITRIX

if(CModule::IncludeModule("main")){
	if(!empty($_REQUEST['REGISTER'])){
		
		if(isset($_REQUEST['REGISTER']['NAME']) && !empty($_REQUEST['REGISTER']['NAME'])){
			$name = htmlspecialchars(trim($_REQUEST['REGISTER']['NAME']));	
		}
		
		$last_name = htmlspecialchars(trim($_REQUEST['REGISTER']['LAST_NAME']));
		$second_name = htmlspecialchars(trim($_REQUEST['REGISTER']['SECOND_NAME']));
		
		if(isset($_REQUEST['REGISTER']['EMAIL']) && !empty($_REQUEST['REGISTER']['EMAIL'])){
			
			$email = htmlspecialchars(trim($_REQUEST['REGISTER']['EMAIL']));
			
				if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					return false;	
				}
		}
		
			$login = htmlspecialchars(trim($_REQUEST['REGISTER']['EMAIL']));
			$login = stristr($login,"@",true);
		
		
		if(isset($_REQUEST['REGISTER']['PERSONAL_PHONE']) && !empty($_REQUEST['REGISTER']['PERSONAL_PHONE']) && strlen($_REQUEST['REGISTER']['PERSONAL_PHONE']) > 10){
			$phone = htmlspecialchars(trim($_REQUEST['REGISTER']['PERSONAL_PHONE']));
		
		}
		if(isset($_REQUEST['CHECKPHONE'])){
			if($_REQUEST['CHECKPHONE'] == "Y"){
				$confirmPhone = 1;	
			}else{
				$confirmPhone = 0;
			}
			
		}
		if(isset($_REQUEST['REGISTER']['PERSONAL_BIRTHDAY']) && !empty($_REQUEST['REGISTER']['PERSONAL_BIRTHDAY'])){
			$birthday = htmlspecialchars(trim($_REQUEST['REGISTER']['PERSONAL_BIRTHDAY']));	
		}
		
		if(isset($_REQUEST['REGISTER']['PERSONAL_GENDER']) && !empty($_REQUEST['REGISTER']['PERSONAL_GENDER'])){
			$gender = htmlspecialchars(trim($_REQUEST['REGISTER']['PERSONAL_GENDER']));	
		}
		
		if(isset($_REQUEST['REGISTER']['PASSWORD']) && !empty($_REQUEST['REGISTER']['PASSWORD'])){
			$password = htmlspecialchars(trim($_REQUEST['REGISTER']['PASSWORD']));	
		}
		if(isset($_REQUEST['REGISTER']['CONFIRM_PASSWORD']) && !empty($_REQUEST['REGISTER']['CONFIRM_PASSWORD'])){
			$confirm_password = htmlspecialchars(trim($_REQUEST['REGISTER']['CONFIRM_PASSWORD']));	
		}
			
			
			
			global $USER;
			
			$arFields = Array(
					'NAME'              => $name,
					'LAST_NAME'         => $last_name,
					'EMAIL'             => $email,
					'LOGIN'             => $email,
					'LID'               => SITE_ID,
					'ACTIVE'            => "Y",
					'GROUP_ID'          => array(3),
					'PASSWORD'          => $password,
					'CONFIRM_PASSWORD'  => $confirm_password,
					'PERSONAL_BIRTHDAY' => $birthday,
					'PERSONAL_GENDER' => $gender,
					'PERSONAL_PHONE' => $phone,
					'SECOND_NAME' => $second_name,
					'UF_CONFIRM_PHONE' => $confirmPhone,
					'PERSONAL_PROFESSION' => 'КП',
				);  
				$USER_ID = $USER->Add($arFields);
				
				
				if (intval($USER_ID) > 0){
					$USER->Authorize($USER_ID);
					$result['status'] = 'success';
					$result['message'] = 'Вы успешно зарегистрировались!';
					
					$arFields['USER_ID'] = $USER_ID;
					
					$arEventFields = $arFields;				
					
					$event = new CEvent;					
					
					$event->SendImmediate("USER_INFO", SITE_ID, $arEventFields);
					
					// Отправляем Оповешение администратору	
					$event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
					
				}
				else{
					$result['status'] = 'error';
					$result['message'] = html_entity_decode($USER->LAST_ERROR);
				}
				
			// if(intval($USER->GetID()) > 0){
				
				// $arUpdateFields = array(
					// 'PERSONAL_BIRTHDAY' => $birthday,
					// 'PERSONAL_GENDER' => $gender,
					// 'PERSONAL_PHONE' => $phone,
					// 'SECOND_NAME' => $second_name,
					// 'UF_CONFIRM_PHONE' => $confirmPhone
				// );
				 
				// $USER->Update($USER->GetID(),$arUpdateFields);		
				
				// $result['success'] = "Y";
				// $result['message'] = 'Вы успешно регистрировались!';
				
			 // }else {
				// $result['status'] = 'error';
			    // $result['message'] = html_entity_decode($CUser->LAST_ERROR);
			// }
		
			
				

		
		echo json_encode($result);
		
	
 }
}

?>

