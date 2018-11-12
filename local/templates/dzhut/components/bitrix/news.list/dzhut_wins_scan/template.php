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
?>

<?/*
	echo '<div class="block-wins">';
	foreach($arResult["ITEMS"] as $arReviews){
		foreach($arReviews["PROPERTIES"]["ATT_PHOTO"]["VALUE"] as $idFile){?>
			<a class="fancybox-win" rel="wins" href="<?echo CFile::GetPath($idFile)?>"><div class="block-win" style="background: url(<?echo CFile::GetPath($idFile)?>) no-repeat center center;"></div></a>
		<?}
	}
	echo '</div>';
*/?>

<div class="block-wins">
	<?foreach($arResult["ITEMS"] as $arElement): ?>
		<?$itemImage = $arElement["PREVIEW_PICTURE"]["SRC"];?>
		<?foreach($arElement["PROPERTIES"]["ATT_PHOTO"]["VALUE"] as $image):?>
			<a class="fancybox-win" rel="wins" href="<?echo CFile::GetPath($image)?>"><div class="block-win"><img src="<?=$itemImage?>" /></div></a>
		<?endforeach;?>
	<?endforeach;?>
</div>
<div class="clear"></div>
<div class="block-wins-2">
	<?foreach($arResult["ITEMS"] as $arElement): ?>
		<?$itemImage = $arElement["PREVIEW_PICTURE"]["SRC"];?>
		<?foreach($arElement["PROPERTIES"]["ATT_PHOTO_2"]["VALUE"] as $image):?>
			<a class="fancybox-win" rel="wins" href="<?echo CFile::GetPath($image)?>"><div class="block-win-2"><img src="<?=$itemImage?>" /></div></a>
		<?endforeach;?>
	<?endforeach;?>
</div>
