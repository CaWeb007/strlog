<?
global $USER;
$id = $USER->GetID();
$rsUser = CUser::GetByID($id);
$arUser = $rsUser->Fetch();

$arResult['USER_EMAIL'] = $arUser['EMAIL'];
$arResult['USER_BONUS'] = $arUser['UF_BONUS'];
