<?//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
// CUSTOM REGISTRATION ON API BITRIX

if(CModule::IncludeModule("main")){
	if(!empty($_REQUEST['REGISTER'])){
		
		if(isset($_REQUEST['REGISTER']['NAME']) && !empty($_REQUEST['REGISTER']['NAME'])){
			$name = htmlspecialchars(trim($_REQUEST['REGISTER']['NAME']));	
		}
		
		$last_name = htmlspecialchars(trim($_REQUEST['REGISTER']['LAST_NAME']));
		
		if(isset($_REQUEST['REGISTER']['EMAIL']) && !empty($_REQUEST['REGISTER']['EMAIL'])){
			
			$email = htmlspecialchars(trim($_REQUEST['REGISTER']['EMAIL']));
			
				if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					return false;	
				}
		}
		if(isset($_REQUEST['REGISTER']['LOGIN']) && !empty($_REQUEST['REGISTER']['LOGIN']) && strlen($_REQUEST['REGISTER']['LOGIN']) > 3){
			$login = htmlspecialchars(trim($_REQUEST['REGISTER']['LOGIN']));
			$login = stristr($login,"@",true);
		}
		
		if(isset($_REQUEST['REGISTER']['PERSONAL_PHONE']) && !empty($_REQUEST['REGISTER']['PERSONAL_PHONE']) && strlen($_REQUEST['REGISTER']['PERSONAL_PHONE']) > 10){
			$phone = htmlspecialchars(trim($_REQUEST['REGISTER']['PERSONAL_PHONE']));
		
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
		
		if($password == $confirm_password){
			
			global $USER;
			$order = array('sort' => 'asc');
			$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
			$arFilter = array(
				'EMAIL' => $email,
			);
			$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("EMAIL","PERSONAL_PHONE")));
			
			if(!empty($rsUsers->NavNext(true, "f_"))){
				
				if($email == $f_EMAIL){
						if($phone == $f_PERSONAL_PHONE){
							$jsonResponse = 3;
							echo $jsonResponse;
							return false;
								
						}else{
							$jsonResponse = 4;
							echo $jsonResponse;
							return false;							
						}
				}
			
			}
			
			$resultReg = $USER->Register($login,$name,$last_name,$password,$confirm_password,$email);
				
			if($resultReg){
				
				$arUpdateFields = array(
				'PERSONAL_BIRTHDAY' => $birthday,
				'PERSONAL_GENDER' => $gender,
				'PERSONAL_PHONE' => $phone
				);
				 
				if($USER->Update($USER->GetID(),$arUpdateFields)){

					$jsonResponse = 1;
					echo $jsonResponse;
				}
			}
	}
	else{
			$jsonResponse = 2;
		    echo $jsonResponse;
		}
	
}
}

?>

