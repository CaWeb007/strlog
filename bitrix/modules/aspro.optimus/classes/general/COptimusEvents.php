<?
if(!defined('OPTIMUS_MODULE_ID'))
	define('OPTIMUS_MODULE_ID', 'aspro.optimus');

use \Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	\Bitrix\Main\Config\Option,
	Bitrix\Main\IO\File,
	Bitrix\Main\Page\Asset;
Loc::loadMessages(__FILE__);

class COptimusEvents{
	const partnerName	= 'aspro';
	const solutionName	= 'optimus';
	const moduleID		= OPTIMUS_MODULE_ID;
	const wizardID		= 'aspro:optimus';
	
	static function OnBeforeSubscriptionAddHandler(&$arFields){
		if(!defined('ADMIN_SECTION'))
		{
			global $APPLICATION;
			if(\Bitrix\Main\Loader::includeModule(OPTIMUS_MODULE_ID))
			{
				$show_licenses = Option::get(self::moduleID, 'SHOW_LICENCE', 'Y', SITE_ID);
				if($show_licenses == 'Y' && !isset($_REQUEST['licenses_subscribe']))
				{
					$APPLICATION->ThrowException(Loc::getMessage('ERROR_FORM_LICENSE'));
					return false;
				}
			}
		}
	}

	function OnFindSocialservicesUserHandler($arFields){
		// check for user with email
		if($arFields['EMAIL'])
		{
			$arUser = CUser::GetList($by = 'ID', $ord = 'ASC', array('EMAIL' => $arFields['EMAIL'], 'ACTIVE' => 'Y'), array('NAV_PARAMS' => array("nTopCount" => "1")))->fetch();
			if($arUser)
			{
				if($arFields['PERSONAL_PHOTO'])
				{
					
					/*if(!$arUser['PERSONAL_PHOTO'])
					{
						$arUpdateFields = Array( 
							'PERSONAL_PHOTO' => $arFields['PERSONAL_PHOTO'], 
						); 
						$user->Update($arUser['ID'], $arUpdateFields);
					}
					else
					{*/
						$code = 'UF_'.strtoupper($arFields['EXTERNAL_AUTH_ID']);
						$arUserFieldUserImg = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'USER', 'FIELD_NAME' => $code))->Fetch();
						if(!$arUserFieldUserImg)
						{
							$arFieldsUser = array(
								"FIELD_NAME" => $code,
								"USER_TYPE_ID" => "file",
								"XML_ID" => $code,
								"SORT" => 100,
								"MULTIPLE" => "N",
								"MANDATORY" => "N",
								"SHOW_FILTER" => "N",
								"SHOW_IN_LIST" => "Y",
								"EDIT_IN_LIST" => "Y",
								"IS_SEARCHABLE" => "N",
								"SETTINGS" => array(
									"DISPLAY" => "LIST",
									"LIST_HEIGHT" => 5,
								)
							);
							$arLangs = array(
								"EDIT_FORM_LABEL" => array(
									"ru" => $code,
									"en" => $code,
								),
								"LIST_COLUMN_LABEL" => array(
									"ru" => $code,
									"en" => $code,
								)
							);

							$ob = new CUserTypeEntity();
							$FIELD_ID = $ob->Add(array_merge($arFieldsUser, array('ENTITY_ID' => 'USER'), $arLangs));

						}
						$user = new CUser;
						$arUpdateFields = Array( 
							$code => $arFields['PERSONAL_PHOTO'], 
						); 
						$user->Update($arUser['ID'], $arUpdateFields);
					//}
				}
				return $arUser['ID'];
			}
		}
		return false;
	}
	
	function OnAfterSocServUserAddHandler( $arFields ){
		if($arFields["EMAIL"]){
			global $USER;
			$userEmail=$USER->GetEmail();
			$email=(is_null($userEmail) ? $arFields["EMAIL"] : $userEmail );
			//$resUser = CUser::GetList(($by="ID"), ($order="asc"), array("=EMAIL" => $arFields["EMAIL"]), array("FIELDS" => array("ID")));
			$resUser = CUser::GetList(($by="ID"), ($order="asc"), array("=EMAIL" => $email), array("FIELDS" => array("ID")));
			$arUserAlreadyExist = $resUser->Fetch();

			if($arUserAlreadyExist["ID"]){
				\Bitrix\Main\Loader::includeModule('socialservices');
				global $USER;
				if($resUser->SelectedRowsCount()>1){
					CSocServAuthDB::Update($arFields["ID"], array("USER_ID" => $arUserAlreadyExist["ID"], "CAN_DELETE" => "Y"));
					CUser::Delete($arFields["USER_ID"]);
					$USER->Authorize($arUserAlreadyExist["ID"]);
				}else{
					$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
					if($def_group!=""){
						$GROUP_ID = explode(",", $def_group);
						$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
					}else{
						$arPolicy = $USER->GetGroupPolicy(array());
					}
					$password_min_length = (int)$arPolicy["PASSWORD_LENGTH"];
					if($password_min_length <= 0)
						$password_min_length = 6;
					$password_chars = array(
						"abcdefghijklnmopqrstuvwxyz",
						"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
						"0123456789",
					);
					if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
						$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
					$NEW_PASSWORD = $NEW_PASSWORD_CONFIRM = randString($password_min_length+2, $password_chars);

					$user = new CUser;
					$arFieldsUser = Array(
					  "NAME"              => $arFields["NAME"],
					  "LAST_NAME"         => $arFields["LAST_NAME"],
					  "EMAIL"             => $arFields["EMAIL"],
					  "LOGIN"             => $arFields["EMAIL"],
					  "GROUP_ID"          => $GROUP_ID,
					  "PASSWORD"          => $NEW_PASSWORD,
					  "CONFIRM_PASSWORD"  => $NEW_PASSWORD_CONFIRM,
					);
					unset($arFields["LOGIN"]);
					unset($arFields["PASSWORD"]);
					unset($arFields["EXTERNAL_AUTH_ID"]);
					unset($arFields["XML_ID"]);
					$arAddFields = array();
					$arAddFields = array_merge($arFieldsUser, $arFields);
					if(isset($arAddFields["PERSONAL_PHOTO"]) && $arAddFields["PERSONAL_PHOTO"])
					{
						$arPic = CFile::MakeFileArray($arFields["PERSONAL_PHOTO"]);
						$arAddFields["PERSONAL_PHOTO"] = $arPic;
					}

					//if($arUserAlreadyExist["ID"]!=$arFields["USER_ID"]){
						$ID = $user->Add($arAddFields);
						//$ID = $user->Add($arFieldsUser);
						CSocServAuthDB::Update($arFields["ID"], array("USER_ID" => $ID, "CAN_DELETE" => "Y"));
						CUser::Delete($arFields["USER_ID"]);
						$USER->Authorize($ID);
					//}
				}
			}
		}
	}

	public static function OnPageStartHandler(){
		
		if(defined("ADMIN_SECTION") || !\Aspro\Functions\CAsproOptmusReCaptcha::checkRecaptchaActive())
			return;

		$captcha_public_key = \Aspro\Functions\CAsproOptmusReCaptcha::getPublicKey();
		$assets = Asset::getInstance();

		$arCaptchaProp = array();
		$arCaptchaProp['recaptchaColor'] = strtolower(Option::get(self::moduleID, 'GOOGLE_RECAPTCHA_COLOR', 'LIGHT'));
		$arCaptchaProp['recaptchaLogoShow'] = strtolower(Option::get(self::moduleID, 'GOOGLE_RECAPTCHA_SHOW_LOGO', 'Y'));
		$arCaptchaProp['recaptchaSize'] = strtolower(Option::get(self::moduleID, 'GOOGLE_RECAPTCHA_SIZE', 'NORMAL'));
		$arCaptchaProp['recaptchaBadge'] = strtolower(Option::get(self::moduleID, 'GOOGLE_RECAPTCHA_BADGE', 'BOTTOMRIGHT'));
		$arCaptchaProp['recaptchaLang'] = LANGUAGE_ID;

		//add global object asproRecaptcha
		$scripts = "<script type='text/javascript' data-skip-moving='true'>";
		$scripts .= "window['asproRecaptcha'] = {params: ".\CUtil::PhpToJsObject($arCaptchaProp).",key: '".$captcha_public_key."'};";
		$scripts .= "</script>";
		$assets->addString($scripts);

		//add scripts
		$scriptsDir = $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.self::moduleID.'/captcha/';
		$scriptsPath = File::isFileExists($scriptsDir.'recaptcha.min.js')? $scriptsDir.'recaptcha.min.js' : $scriptsDir.'recaptcha.js';
		$scriptCode = File::getFileContents($scriptsPath);
		$scripts = "<script type='text/javascript' data-skip-moving='true'>".$scriptCode."</script>";
		$assets->addString($scripts);

		$scriptsPath = File::isFileExists($scriptsDir . 'replacescript.min.js') ? $scriptsDir . 'replacescript.min.js' : $scriptsDir . 'replacescript.js';
		$scriptCode = File::getFileContents($scriptsPath);
		$scripts = "<script type='text/javascript' data-skip-moving='true'>".$scriptCode."</script>";
		$assets->addString($scripts);

		//process post request
		$application = Application::getInstance();
		$request = $application->getContext()->getRequest();
		$arPostData = $request->getPostList()->toArray();

		$needReInit = false;

		if($arPostData['g-recaptcha-response'])
		{
			if($code = \Aspro\Functions\CAsproOptmusReCaptcha::getCodeByPostList($arPostData))
			{
				$_REQUEST['captcha_word'] = $_POST['captcha_word'] = $code;
				$needReInit = true;
			}
		}

		foreach($arPostData as $key => $arPost)
		{
			if(!is_array($arPost) || !$arPost['g-recaptcha-response'])
				continue;

			if($code = \Aspro\Functions\CAsproOptmusReCaptcha::getCodeByPostList($arPost))
			{
				$_REQUEST[$key]['captcha_word'] = $_POST[$key]['captcha_word'] = $code;
				$needReInit = true;
			}
		}

		if($needReInit)
		{
			\Aspro\Functions\CAsproOptmusReCaptcha::reInitContext($application, $request);
		}
	}

	function OnSaleComponentOrderProperties(&$arUserResult, $arRequest, $arParams, $arResult){
		if($arUserResult['ORDER_PROP'] && $arParams['USE_PHONE_NORMALIZATION'] !='N')
		{
			$arPhoneProp = CSaleOrderProps::GetList(
				array('SORT' => 'ASC'),
				array(
						'PERSON_TYPE_ID' => $arUserResult['PERSON_TYPE_ID'],
						'IS_PHONE' => 'Y',
					),
				false,
				false,
				array()
			)->fetch(); // get phone prop
			if($arPhoneProp)
			{
				global $USER;
				if($arUserResult['ORDER_PROP'][$arPhoneProp['ID']])
				{
					if($_REQUEST['order']['ORDER_PROP_'.$arPhoneProp['ID']])
					{
						$arUserResult['ORDER_PROP'][$arPhoneProp['ID']] = $_REQUEST['order']['ORDER_PROP_'.$arPhoneProp['ID']];
					}
					else
					{
						if($arUserResult['PROFILE_ID']) //get phone from user profile
						{
							$arUserPropValue = CSaleOrderUserPropsValue::GetList(
								array('ID' => 'ASC'), 
								array('USER_PROPS_ID' => $arUserResult['PROFILE_ID'], 'ORDER_PROPS_ID' => $arPhoneProp['ID'])
							)->fetch();
							if($arUserPropValue['VALUE'])
							{
								$arUserResult['ORDER_PROP'][$arPhoneProp['ID']] = $arUserPropValue['VALUE'];
							}
						}
						elseif($USER->isAuthorized()) //get phone from user field
						{
							$rsUser = CUser::GetByID($USER->GetID());
							if($arUser = $rsUser->Fetch())
							{
								if(!empty($arUser['PERSONAL_PHONE']))
								{
									$value = $arUser['PERSONAL_PHONE'];
								}
								elseif(!empty($arUser['PERSONAL_MOBILE']))
								{
									$value = $arUser['PERSONAL_MOBILE'];
								}
							}
							if($value)
								$arUserResult['ORDER_PROP'][$arPhoneProp['ID']] = $value;
						}
						if($arUserResult['ORDER_PROP'][$arPhoneProp['ID']]) // add + mark for correct mask
						{
							$mask = \Bitrix\Main\Config\Option::get('aspro.optimus', 'PHONE_MASK', '+7 (999) 999-99-99');
							if(strpos($arUserResult['ORDER_PROP'][$arPhoneProp['ID']], '+') === false && strpos($mask, '+') !== false)
							{
								$arUserResult['ORDER_PROP'][$arPhoneProp['ID']] = '+'.$arUserResult['ORDER_PROP'][$arPhoneProp['ID']];
							}
						}
					}
				}
			}
		}
	}

	function OnSaleComponentOrderOneStepComplete($ID, $arOrder, $arParams){
		$arOrderProps = array();
		$resOrder = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $ID));
		while($item = $resOrder->fetch())
		{
			$arOrderProps[$item['CODE']] = $item;
		}
		$arPhoneProp = CSaleOrderProps::GetList(
			array('SORT' => 'ASC'),
			array(
					'PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID'],
					'IS_PHONE' => 'Y',
				),
			false,
			false,
			array()
		)->fetch(); // get phone prop
		if($arPhoneProp && $arParams['USE_PHONE_NORMALIZATION'] !='N')
		{
			if($arOrderProps[$arPhoneProp['CODE']])
			{
				if($arOrderProps[$arPhoneProp['CODE']]['VALUE'])
				{
					if($_REQUEST['ORDER_PROP_'.$arOrderProps[$arPhoneProp['CODE']]['ORDER_PROPS_ID']])
					{
						CSaleOrderPropsValue::Update($arOrderProps[$arPhoneProp['CODE']]['ID'], array('VALUE'=>$_REQUEST['ORDER_PROP_'.$arOrderProps[$arPhoneProp['CODE']]['ORDER_PROPS_ID']])); // set phone order prop
						$arUserProps = CSaleOrderUserProps::GetList(
							array('DATE_UPDATE' => 'DESC'),
							array('USER_ID' => $arOrder['USER_ID'], 'PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID'])
						)->fetch(); // get user profile info

						if($arUserProps)
						{
							$arUserPropValue = CSaleOrderUserPropsValue::GetList(
								array('ID' => 'ASC'), 
								array('USER_PROPS_ID' => $arUserProps['ID'], 'ORDER_PROPS_ID' => $arOrderProps[$arPhoneProp['CODE']]['ORDER_PROPS_ID'])
							)->fetch(); // get phone from user prop
							if($arUserPropValue['VALUE'])
							{
								CSaleOrderUserPropsValue::Update($arUserPropValue['ID'], array('VALUE'=>$_REQUEST['ORDER_PROP_'.$arOrderProps[$arPhoneProp['CODE']]['ORDER_PROPS_ID']])); //set phone in user profile
							}
						}
					}
				}
			}
		}
	}

	static function OnEndBufferContentHandler(&$content)
	{
		if(!defined('ADMIN_SECTION') && !defined('WIZARD_SITE_ID'))
		{
			global $SECTION_BNR_CONTENT, $APPLICATION;

			if($SECTION_BNR_CONTENT)
			{
				$start = strpos($content, '<!--title_content-->');
				if($start>0)
				{
					$end = strpos($content, '<!--end-title_content-->');

					if(($end>0) && ($end>$start))
					{
						if(defined("BX_UTF") && BX_UTF === true)
							$content = COptimus::utf8_substr_replace($content, "", $start, $end-$start);
						else
							$content = substr_replace($content, "", $start, $end-$start);
					}
				}
				$content = str_replace("wides ", "wides with_banners ", $content);
			}
		}
	}
}