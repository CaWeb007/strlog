<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="wins-list">
<?
	foreach($arResult["ITEMS"] as $arWins){
		foreach($arWins["PROPERTIES"]["ATT_WINS"]["VALUE"] as $idFile){?>
	<div class="wins-item" style="background-image: url(<?echo CFile::GetPath($idFile)?>)">
		<a class="fancybox" data-fancybox-group="gallery" href="<?echo CFile::GetPath($idFile)?>"></a>
	</div>
		<?}
	}
?>
</div>
