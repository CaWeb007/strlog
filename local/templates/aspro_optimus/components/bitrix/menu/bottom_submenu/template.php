<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?$first = true?>
<ul class="submenu">
	<?if (is_array($arResult) && !empty($arResult)):?>
	<?foreach( $arResult as $arItem ){?>
		<li class="menu_item<?=($arItem["SELECTED"]?" selected":"");?>"><a title="<?=$arItem["TEXT"]?>" href="<?=$arItem["LINK"]?>" class="dark_link<?=($first?" bottom_menu_title":"");?>"><?=$arItem["TEXT"]?></a></li>
		<?if (is_array($arResult["ITEMS"]) && !empty($arResult["ITEMS"])):?>
			<?foreach( $arItem["ITEMS"] as $arSubItem ){?>
				<li class="menu_subitem<?=($arItem["SELECTED"]?" selected":"");?>"><a title="<?=$arSubItem["TEXT"]?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a></li>
			<?}?>
		<?endif;?>
        <?$first = false?>
	<?}?>
	<?endif;?>
</ul>