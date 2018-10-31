<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<?/*phpinfo();*/?>

<?php
function testCron(){
    $testCronUser = new CUser;
    $updateTestField = array("UF_BONUSES" => 501);
    $testCronUser -> Update(1, $updateTestField);
}
?>

<?

//Более быстрый способ добавлять и обновлять пользователей
//Выборка всех пользователей из HL блока
$HLBlockUsersEmails = array();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
$HLBlock = HL\HighloadBlockTable::getById(4)->fetch();
$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
$HLObjectClass = $HLObject->getDataClass();
$HLResultArray = $HLObjectClass::getList(array(
    'select' => array('*'),
));
while ($HLResult = $HLResultArray->fetch()){
    $HLBlockUsersEmails[$HLResult["UF_ELEKTRONNAYAPOCHT"]] = $HLResult;
}

//Выборка всех зарегистрированных пользователей из БД
$SystemUsersEmails = array();
$arrUsers = Bitrix\Main\UserTable::getList(array(
    'select' => array('*'),
));
while ($arrUserData = $arrUsers->fetch()) {
    $SystemUsersEmails[$arrUserData["EMAIL"]] = $arrUserData;
}

//Сравнение массивов
$intersectEmails = array_intersect_key($HLBlockUsersEmails, $SystemUsersEmails);
$diffEmails = array_diff($HLBlockUsersEmails, $SystemUsersEmails);

if($USER->IsAdmin()) {
    /*echo '<pre>';
    print_r($intersectEmails);
    echo '</pre>';*/
    echo "<div style='width:100%;height:5px;background:#000;'></div>";
    /*echo '<pre>';
    print_r($diffEmails);
    echo '</pre>';*/
}

?>

<?
//Начинаем записывать логи
/*define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/user/log.txt");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");*/
?>

<?
/*
//Обновление списка пользователей из HighLoad блока -> Удаление списка пользователей из HighLoad блока
$HLUsers = array();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
$HLBlock = HL\HighloadBlockTable::getById(4)->fetch();
$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
$HLObjectClass = $HLObject->getDataClass();
$HLResultArray = $HLObjectClass::getList(array(
    'select' => array('*'),
));
while ($HLResult = $HLResultArray->fetch()){
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
    $HLUserBonusesID = $HLResult["UF_XML_ID"];
    $HLUserGroup = $HLResult["UF_GRUPPANASAYTE"];

    //Обновление существующих пользователей
    $arrUsers = Bitrix\Main\UserTable::getList(array(
        'select' => array('ID', 'EMAIL'),
        'filter' => array('EMAIL' => $HLUserEmail),
    ));
    while ($arrUserData = $arrUsers->fetch()) {
        $userID = $arrUserData["ID"];
        $user = new CUser;
        $userUpdateFields = array(
            "NAME" => $HLUserName,
            "SECOND_NAME" => " ",
            "LAST_NAME" => " ",
            "LOGIN" => $HLUserEmail,
            "EMAIL" => $HLUserEmail,
            "PERSONAL_PHONE" => $HLUserPhone,
            "PERSONAL_PROFESSION" => $HLUserGroup,
            "UF_BONUSES" => $HLUserBonuses,
            "UF_INN" => $HLUserINN,
            "UF_KPP" => $HLUserKPP,
            "UF_BONUS_HISTORY_ID" => $HLUserBonusesID,
            "GROUP_ID" => array(2, 3, 4, 5, 6, $userSpecGroup),
        );
        $user->Update($userID, $userUpdateFields);
        $HLObjectClass::Delete($HLUserID);//Удаление списка существующих пользователей из HighLoad блока
    }

    $userAdd = new CUser;
    $userAddFields = array(
        "NAME" => $HLUserName,
        "SECOND_NAME" => " ",
        "LAST_NAME" => " ",
        "LOGIN" => $HLUserEmail,
        "EMAIL" => $HLUserEmail,
        "PERSONAL_PHONE" => $HLUserPhone,
        "PERSONAL_PROFESSION" => $HLUserGroup,
        "UF_BONUSES" => $HLUserBonuses,
        "UF_INN" => $HLUserINN,
        "UF_KPP" => $HLUserKPP,
        "UF_BONUS_HISTORY_ID" => $HLUserBonusesID,
        "GROUP_ID" => array(2, 3, 4, 5, 6, $userSpecGroup),
        "LID" => "ru",
        "ACTIVE" => "Y",
        "PASSWORD" => "01012018",
        "CONFIRM_PASSWORD" => "01012018",
    );
    $ID = $userAdd->Add($userAddFields);
    if (IntVal($ID) > 0) {
        AddMessage2Log("Пользователь: " . $HLUserEmail . '  добавлен');
        //$HLObjectClass::Delete($HLUserID);//Удаление списка добавленных пользователей из HighLoad блока
    } else {
        AddMessage2Log("Ошибка добавления пользователя " . $HLUserEmail);
    }
}
*/
?>

<?
/*
//Добавление пользователей из highload блока
$HLUsers = array();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

$hlblock = HL\HighloadBlockTable::getById(4)->fetch(); // id highload блока
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entityClass = $entity->getDataClass();

$res = $entityClass::getList(array(
    'select' => array('*'),
));
while ($rows = $res->fetch()){
    $HLUsers[$rows["UF_ELEKTRONNAYAPOCHT"]] = $rows;
}
$arrHighLoadBlockUsers = $HLUsers;
if($USER->IsAdmin()) {
    foreach ($arrHighLoadBlockUsers as $arrHighLoadBlockUserKey => $arrHighLoadBlockUserValue){
        if ($arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"] == "КП(ФИЗ)") {
            $userSpecGroup = 9;
        } elseif ($arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"] == "КП(ЮР)") {
            $userSpecGroup = 14;
        } elseif ($arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"] == "СО(КМС)") {
            $userSpecGroup = 10;
        } elseif ($arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"] == "СО(ПГС)") {
            $userSpecGroup = 12;
        } elseif ($arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"] == "ТО") {
            $userSpecGroup = 11;
        }
        $user = new CUser;
        $arrFields = array(
            "NAME" => $arrHighLoadBlockUserValue["UF_NAME"],
            "SECOND_NAME" => "",
            "LAST_NAME" => " ",
            "EMAIL" => $arrHighLoadBlockUserValue["UF_ELEKTRONNAYAPOCHT"],
            "LOGIN" => $arrHighLoadBlockUserValue["UF_ELEKTRONNAYAPOCHT"],
            "LID" => "ru",
            "ACTIVE" => "Y",
            "GROUP_ID" => array(2, 3, 4, 5, 6, $userSpecGroup),
            "PASSWORD" => "01012018",
            "CONFIRM_PASSWORD" => "01012018",
            "UF_BONUSES" => $arrHighLoadBlockUserValue["UF_OSTATOKBONUSOV"],
            "PERSONAL_PROFESSION" => $arrHighLoadBlockUserValue["UF_GRUPPANASAYTE"],
        );
        $ID = $user->Add($arrFields);
        if (IntVal($ID) > 0) {
            echo 'User access';
        } else {
            $user->LAST_ERROR;
        }
    }
}
*/
?>

<?
/*
//Список пользователь в HighLoad блоке
$HLUsers = array();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
$HLBlock = HL\HighloadBlockTable::getById(4)->fetch(); // id highload блока
$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
$HLObjectClass = $HLObject->getDataClass();
$HLResultArray = $HLObjectClass::getList(array(
    'select' => array('*'),
));
while ($HLResult = $HLResultArray->fetch()){
    //$HLUsers[$HLResult["UF_ELEKTRONNAYAPOCHT"]] = $HLResult;
    $HLUserName = $HLResult["UF_NAME"];
    $HLUserINN = $HLResult["UF_INN"];
    $HLUserKPP = $HLResult["UF_KPP"];
    $HLUserPhone = $HLResult["UF_TELEFONKONTRAGENT"];
    $HLUserEmail = $HLResult["UF_ELEKTRONNAYAPOCHT"];
    $HLUserBonuses = $HLResult["UF_OSTATOKBONUSOV"];
    $HLUserGroup = $HLResult["UF_GRUPPANASAYTE"];
    if($USER->IsAdmin()) {
        echo $HLResult["ID"] . ' - ' . $HLUserEmail . ' - ' . $HLUserBonuses . '<br />';
    }
}
*/
?>

<?
/*
$order = array('sort' => 'asc');
$tmp = 'sort';
$rsUsers = CUser::GetList($order, $tmp);
while ($arrUser = $rsUsers->GetNext()) {
	$userID = $arrUser["ID"];
	$userName = $arrUser["NAME"];
	$userLogin = $arrUser["LOGIN"];
	if($userID != 1 || $userID != 2){
        $arGroupAvalaible = array(14); // массив групп, которые в которых нужно проверить доступность пользователя
        $arGroups = CUser::GetUserGroup($userID); // массив групп, в которых состоит пользователь
        $result_intersect = array_intersect($arGroupAvalaible, $arGroups);// далее проверяем, если пользователь вошёл хотя бы в одну из групп, то позволяем ему что-либо делать
        if(!empty($result_intersect)){
            echo '<pre>';
            print_r($result_intersect);
            echo '</pre>';
            echo 'ID'.$userID.'Имя: '.$userName.'<br />';
            //$user = new CUser;
            //$updateField = array("PERSONAL_PROFESSION"=>"КП");
            //$user->Update($userID, $updateField);
        }
		echo $userID.' - '.$userName.' - '.$userLogin.'<br />';
        //CUser::SetUserGroup($userID, array(3, 4, 5, 6, 9));
	}else{

	}

}
*/
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>