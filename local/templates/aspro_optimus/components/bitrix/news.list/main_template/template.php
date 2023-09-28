<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if (count($arResult["ITEMS"])):?>
	<div class="articles-list lists_block news <?=($arParams["IS_VERTICAL"]=="Y" ? "vertical row" : "")?> <?=($arParams["SHOW_FAQ_BLOCK"]=="Y" ? "faq" : "")?> ">
		<?
			foreach($arResult["ITEMS"] as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$arSize=array("WIDTH"=>280, "HEIGHT" => 190);
				if($arParams["SHOW_FAQ_BLOCK"]=="Y"){
					if($arParams["IS_VERTICAL"]!="Y")
						$arSize=array("WIDTH"=>175, "HEIGHT" => 120);
				}else{
					if($arParams["IS_VERTICAL"]!="Y")
						$arSize=array("WIDTH"=>220, "HEIGHT" => 270);
				}
				if(isset($arParams["BIG_IMG"]) && $arParams["BIG_IMG"] == "Y")
					$arSize=array("WIDTH"=>400, "HEIGHT" => 290);
                $link = $arItem["DETAIL_PAGE_URL"];
                if ($link && $arItem['PROPERTIES']['MARKER_ORD']['VALUE'])
                    $link = \Caweb\Main\Tools::getInstance()->getMarkerOrdUri($arItem['PROPERTIES']['MARKER_ORD']['VALUE'] , $link);
		?>
			<div class="item clearfix item_block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="wrapper_inner_block">
                    <div class="left-data">
                        <?if($arItem["PREVIEW_PICTURE"]):?>
                            <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
                                <a href="<?=$link?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
                        <?elseif($arItem["DETAIL_PICTURE"]):?>
                            <?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
                                <a href="<?=$link?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"] ? $arItem["DETAIL_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"] ? $arItem["DETAIL_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
                        <?else:?>
                                <a href="<?=$link?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" height="90" /></a>
                        <?endif;?>
                        <?if ($link && $arItem['PROPERTIES']['MARKER_ORD']['VALUE']):?>
                            <div class="ord-link">
                                Реклама
                                <i class="fa fa-angle-right"></i>
                                <input type="text" class="ord-link-href" value="<?=$link?>">
                            </div>
                        <?endif?>
                    </div>

                    <div class="right-data">
						<?if($arParams["DISPLAY_DATE"]=="Y"){?>
							<?if( $arItem["PROPERTIES"]["PERIOD"]["VALUE"] ){?>
								<div class="date_small"><?=$arItem["PROPERTIES"]["PERIOD"]["VALUE"]?></div>
							<?}elseif($arItem["DISPLAY_ACTIVE_FROM"]){?>
								<div class="date_small"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
							<?}?>
						<?}?>
						<div class="item-title"><a title="<?=$arItem["NAME"]?>" href="<?=$link?>"><span><?=$arItem["NAME"]?></span></a></div>

						<?if($arParams["SHOW_PREVIEW_TEXT"] != 'N'):?>
							<div class="preview-text"><?=$arItem["PREVIEW_TEXT"]?></div>
						<?endif;?>

						<?if($arItem["PROPERTIES"][$arParams["PRICE_PROPERTY"]]["VALUE"]):?>
							<div class="price services">
								<div class="price_new">
									<?=$arItem["PROPERTIES"][$arParams["PRICE_PROPERTY"]]["VALUE"];?>
								</div>
							</div>
						<?endif;?>
					</div>
					<div class="clear"></div>
				</div>

			</div>
		<?}?>
	</div>
	<?if( $arParams["DISPLAY_BOTTOM_PAGER"] ){?><?=$arResult["NAV_STRING"]?><?}?>
<?endif;?>