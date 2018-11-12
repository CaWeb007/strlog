<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>
<div class="news-list">
<h2><a title="Новости" href="/novosti/">Новости</a></h2>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<?/*$DateStringTime = $arItem['DATE_CREATE'];
			$DateElems = explode(" ",$DateStringTime);
				$DateElemtUnix = explode(".",$DateElems[0]);
				$ResUnixTime = mktime(0,0,0,$DateElemtUnix[1],$DateElemtUnix[0],$DateElemtUnix[2]);
				$CreateTime = CIBlockFormatProperties::DateFormat("d F Y", $ResUnixTime);*/?>

	<?$DateStringTime = $arItem['TIMESTAMP_X'];
			$DateElems = explode(" ",$DateStringTime);
				$DateElemtUnix = explode(".",$DateElems[0]);
				$ResUnixTime = mktime(0,0,0,$DateElemtUnix[1],$DateElemtUnix[0],$DateElemtUnix[2]);
				$CreateTime = CIBlockFormatProperties::DateFormat("d F Y", $ResUnixTime);?>
	<div class="date-news"><?=$CreateTime;?></div>
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
<img class="news-picture" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"/>
	<?endif;?>
	<div class="new-slug">
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
<a title="<?echo $arItem["NAME"]?>" href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a>
			<?else:?>
				<?echo $arItem["NAME"]?>
			<?endif;?>
		<?endif;?>
	</div>		
	</div>
<?endforeach;?>

</div>
