<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult){?>
	<div class="submenu_top rows_block">
		<?if (is_array($arResult) && !empty($arResult)):?>
			<?foreach( $arResult as $arItem ){?>
				<div class="item_block col-5">
					<div class="menu_item"><?if($arItem["LINK"]):?><a href="<?=$arItem["LINK"]?>" class="dark_link"><?=$arItem["TEXT"]?></a><?else:?><span class="dark_link"><?=$arItem["TEXT"]?></span><?endif?></div>
				</div>
			<?}?>
		<?endif;?>
	</div>
<?}?>