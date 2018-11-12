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

<?
	echo '<div class="reviews-scan-list">';
	foreach($arResult["ITEMS"] as $arReviews){
		foreach($arReviews["PROPERTIES"]["ATT_PHOTO"]["VALUE"] as $idFile){?>
			<a class="fancybox-review" rel="reviews" href="<?echo CFile::GetPath($idFile)?>"><div class="review-scan-image" style="background: url(<?echo CFile::GetPath($idFile)?>) no-repeat center center;"></div></a>
		<?}
	}
	echo '</div>';
?>
