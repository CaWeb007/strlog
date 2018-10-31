<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<?
/*
$arParams["SELECT"] = array( "UF_ACCUMULATION" );
$arParams["FIELDS"] = ['ID',]
$ires = CUser::GetList(
 ($by = "NAME"), ($order = "desc"),
 array(),
 $arParams
);
		
while($arItem = $ires->Fetch()){
	varDump($arItem);
}

/*global $DB;

	$arFilter = array(
		'IBLOCK_ID' => 16
	);
		$el = new CIBlockElement();

		$ires = $el::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('ACTIVE' => ['LOGIC'=>'OR','Y','N'])), false, false, ['ID',"PROPERTY_NOT_WORK"]);

		while($arItem = $ires->Fetch()){

	#		$PRODUCT_ID = $arItem["ID"];
	##		if($arItem["PROPERTY_NOT_WORK_VALUE"] == "ДА"){
	#			$DB->query("UPDATE b_catalog_product SET `QUANTITY` = '0' WHERE `ID` = '".$PRODUCT_ID."'");
	#			$el->update($PRODUCT_ID,['ACTIVE'=>'N']);
	#		}
		#	var_dump($PRODUCT_ID);
		#	$arLoadProductArray = ["ACTIVE" => "Y",'DATE_ACTIVE_FROM' => ''];//date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")),  mktime(0,0,0,1,1,2003))];
		#	$br = $el->Update($PRODUCT_ID, $arLoadProductArray, $WF=="Y", true, true);

		}*/
?>
<?
/*
$f=CEvent::CheckEvents();
varDump($f);*/
		/* Отправка пиьсма с паролем *
		$arEventFields= array(
			"LOGIN" => "3wer322FuFDy",
			"NAME" => "sdfs345",
			"PASSWORD" => "FFFFklyiotyuyiyuiyi",
			"EMAIL" => "mailcent@yandex.ru",
			"SERVER_NAME" => "stroylogistika.ru",
			);
		$CV = CEvent::Send("USER_INFO_STRLOG", SITE_ID, $arEventFields, "N", 95,[],LANGUAGE_ID);
	
	/*$varDump($CV->isSuccess());		
		
		$DD=\Bitrix\Main\Mail\Event::send(array(
    "EVENT_NAME" => "USER_INFO_STRLOG",
    "LID" => SITE_ID,
	"DUPLICATE" => "N",
	"MESSAGE_ID" => 95,
	"LANGUAGE_ID" => LANGUAGE_ID,
    "C_FIELDS" => array(
        "EMAIL" => "pavel_06@bk.ru",
		"NAME" => "Twef",
		"PASSWORD" => "6345345",
		"SERVER_NAME" => "stroylogistika.ru",
		"NAME" => "Васрож",
		"DEFAULT_EMAIL_FROM"=>"asdas@stroylogistika.ru"
    ),
));
	varDump($DD->isSuccess());*/	
/*
CModule::IncludeModule('sale');

$arrUsers = Bitrix\Main\UserTable::getList(array(
        'select' => array('ID', 'UF_BONUSES'),
        'filter' => array(),
		'order' => ['ID'=>'ASC']
    ));
    while ($arrUserData = $arrUsers->fetch()) {
		/* ADD or UPDATE User budget *
			$HLUserBonuses = (float)$arrUserData['UF_BONUSES'];
			$userID = $arrUserData['ID'];
var_dump($HLUserBonuses,$userID);
		if($userID > 0){
			$account = CSaleUserAccount::GetByUserID($userID, "RUB");
var_dump($account);
			$arFields = Array("USER_ID" => $userID, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $HLUserBonuses);
			if(!$account) {
				$accountID = CSaleUserAccount::Add($arFields);
			} else {
				if((int)$account['ID'] > 0)
					$accountID = CSaleUserAccount::Update($account['ID'],$arFields);
			}
		}
	}*/
/*if ($ar = CSaleUserAccount::GetByUserID(1111, "RUB")) 
 { echo "На счете ".SaleFormatCurrency($ar["CURRENT_BUDGET"], $ar["CURRENCY"]); } 
else {var_dump($ar);}*/
/*
$arFilter = array(
		'IBLOCK_ID' => 16,
		'ACTIVE' => 'Y'
	);

$dbPriceType = CCatalogGroup::GetList(
	['NAME'=>"ASC"],
	['NAME'=>['LOGIC'=>'OR','ТО','СО','КП']],
	false,
	false,
	['NAME','ID']
);

while ($arPriceType = $dbPriceType->Fetch())
{
	$PRICE_ID[$arPriceType['ID']] = $arPriceType['NAME'];
}
//var_dump("<pre>",$PRICE_ID);

$res = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('>ID' => 0)), false, false, ['ID','CATALOG_GROUP_9','CATALOG_GROUP_10','CATALOG_GROUP_11']);
$errorMessage = null;
echo "<pre>";
while ($arItem = $res->Fetch()) {			

	$ELEMENT_ID = $arItem["ID"];

	$PRICES = [];

	for($i=9;$i<12;$i++){
		if(isset($arItem["CATALOG_PRICE_".$i])){
			$PRICES[$PRICE_ID[$i]] = (float)$arItem["CATALOG_PRICE_".$i];
		}
	}

	if(0 < count($PRICES)){
		$PROPERTIES = [
			"BONUS_KP" => ($PRICES['КП'] - $PRICES['ТО']) * 0.1,
			"BONUS_SO" => ($PRICES['СО'] - $PRICES['ТО']) * 0.1,
			"BONUS_SO15" => ($PRICES['СО'] - $PRICES['ТО']) * 0.15,
			"BONUS_SO20" => ($PRICES['СО'] - $PRICES['ТО']) * 0.20,
		];

	var_dump($ELEMENT_ID,$PRICES,$PROPERTIES,"<br>");
	//	foreach($PROPERTIES as $PROPERTY_CODE=>$PROPERTY_VALUE){
			CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, $PROPERTIES);
	//	}
	}

}
echo "</pre>";*/
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>