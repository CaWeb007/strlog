<?//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION, $LOGIN, $PASSWORD, $MESSAGE, $REQUEST_URL;
// SMS
$LOGIN = 'Strlog';
$PASSWORD = md5(765619);
$MESSAGE = 'Ваш код ';
$REQUEST_URL = 'https://mcommunicator.ru/m2m/m2m_api.asmx/SendMessage';
// CODE_CONFIRM
function SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION){
	
	$CODE = rand(1000,10000);

	$HashCode = md5($CODE);

	$MESSAGE .= $CODE;
	
	$POSTFIELDS = 'msid='.$MSID.'&message='.$MESSAGE.'&naming=POLZAIRK&login='.$LOGIN.'&password='.$PASSWORD.'';
	
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
		$arFilter = array(
			"EMAIL" => $email
		);
		$rsUsers = CUser::GetList($order, $tmp, $arFilter,array("FIELDS"=>array("EMAIL","PERSONAL_PHONE")));
		
		if(!empty($rsUsers->NavNext(true, "f_"))){
			
			if($email == $f_EMAIL){
					if($phone == $f_PERSONAL_PHONE){

						$Data = array("email"=>$f_EMAIL,"phone"=>$f_PERSONAL_PHONE);
						echo $DataJSON = json_encode($Data);
							
					}else{
						$Data = array("email"=>$f_EMAIL);
						echo $DataJSON = json_encode($Data);				
					}
			} else {
				$Data = array("email" => $f_EMAIL, "subemail" => "Y");
				echo $DataJSON = json_encode($Data);				
			}
		// CODE
		}else{
			//codeConfirm($APPLICATION);
			SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION);
		}
	}
	// CHECK CODE
	if(isset($_POST['Code'])){
		
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
		SendMessage($LOGIN,$PASSWORD,$MSID,$MESSAGE,$REQUEST_URL,$APPLICATION);
		
	}	
	

	
}
?>