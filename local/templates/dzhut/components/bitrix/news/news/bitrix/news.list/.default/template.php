<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?><br>
<div class="news-page-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-page-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="news-page-img">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"/></a>
		<?endif?>
		</div>
		<div class="wrap-content-news">
			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<h3 class="slug-news"><a title="<?echo $arItem["NAME"]?>" href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h3>
			<?else:?>
				<h3 class="slug-news"><?echo $arItem["NAME"]?></h3>
			<?endif;?>
		<?endif;?>
		
		<?$DateStringTime = $arItem['DISPLAY_ACTIVE_FROM'];
				$DateElems = explode(" ",$DateStringTime);
					$DateElemtUnix = explode(".",$DateElems[0]);
					$ResUnixTime = mktime(0,0,0,$DateElemtUnix[1],$DateElemtUnix[0],$DateElemtUnix[2]);
					$CreateTime = CIBlockFormatProperties::DateFormat("d F Y", $ResUnixTime);?>
					
		<div class="date-page-news"><?=$arItem['DISPLAY_ACTIVE_FROM'];?></div>
	

		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
		<div class="news-anons"><p><?echo $arItem["PREVIEW_TEXT"];?></p></div>
		<?endif;?>
		</div>
	</div>
<?endforeach;?>

</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>