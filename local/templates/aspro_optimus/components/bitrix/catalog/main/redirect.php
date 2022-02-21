<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/**@var $arResult array*/
$res = CIBlockElement::GetList(
	Array("ID"=>"DESC"),
	Array("IBLOCK_ID" => 16, "XML_ID"=>$arResult['VARIABLES']['XML_ID']),
	false,
	Array(),
	Array("IBLOCK_ID","DETAIL_PAGE_URL")
);
$arItem = $res->GetNext();

if(strlen($arItem['DETAIL_PAGE_URL']))
{
	LocalRedirect($arItem['DETAIL_PAGE_URL'], true, "301 Moved permanently");
}
else
	echo 'Упс! Товар не найден :(';
?>