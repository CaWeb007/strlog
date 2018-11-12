<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="block-photo">
	<?foreach($arResult["ITEMS"] as $arElement): ?>
		<?$itemImage = $arElement["PREVIEW_PICTURE"]["SRC"];?>
		<?foreach($arElement["PROPERTIES"]["ATT_PHOTO"]["VALUE"] as $image):?>
			<a class="block-ukladka-photos" rel="block-ukladka-photos" href="<?echo CFile::GetPath($image)?>">
				<div class="block-ukladka-photo"><img src="<?=$itemImage?>" /></div>
			</a>
		<?endforeach;?>
	<?endforeach;?>
</div>
