<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<?if ($arParams["SHOW_LINKED_PRODUCTS"]=="Y" && $arResult["DISPLAY_PROPERTIES"][$arParams["LINKED_PRODUCTS_PROPERTY"]]["VALUE"]):?>
		<hr class="long"/>
		<div class="similar_products_wrapp main_temp">
				<?if(CSite::InDir(SITE_DIR."sale")){?>
					<h3><?=GetMessage("ACTION_PRODUCTS");?></h3>
				<?}else{?>
					<h3><?=GetMessage("ACTION_PRODUCTS_LINK");?></h3>
				<?}?>
				<?if(!$arParams["CATALOG_FILTER_NAME"]){
					$arParams["CATALOG_FILTER_NAME"]="arrProductsFilter";
				}?>
				<div class="module-products-corusel product-list-items catalog">
					<?$GLOBALS[$arParams["CATALOG_FILTER_NAME"]] = array("ID" => $arResult["DISPLAY_PROPERTIES"][$arParams["LINKED_PRODUCTS_PROPERTY"]]["VALUE"] );?>
					<?$list_view = ($arParams['LIST_VIEW'] ? $arParams['LIST_VIEW'] : 'slider');?>
                    <?$arParams['LINK_IBLOCK_ID'] = '16'?>
					<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/news.detail.products_'.$list_view.'.php');?>
				</div>
		</div>
	<?elseif ($arParams["SHOW_LINKED_PRODUCTS"]=="Y" && $arResult["DISPLAY_PROPERTIES"]['NK']["VALUE"]):?>
        <hr class="long"/>
        <div class="similar_products_wrapp main_temp">
            <?if(CSite::InDir(SITE_DIR."sale")){?>
                <h3><?=GetMessage("ACTION_PRODUCTS");?></h3>
            <?}else{?>
                <h3><?=GetMessage("ACTION_PRODUCTS_LINK");?></h3>
            <?}?>
            <?if(!$arParams["CATALOG_FILTER_NAME"]){
                $arParams["CATALOG_FILTER_NAME"]="arrProductsFilter";
            }?>
            <div class="module-products-corusel product-list-items catalog">
                <?$GLOBALS[$arParams["CATALOG_FILTER_NAME"]] = array("ID" => $arResult["DISPLAY_PROPERTIES"]['NK']["VALUE"] );?>
                <?$list_view = ($arParams['LIST_VIEW'] ? $arParams['LIST_VIEW'] : 'slider');?>
                <?$arParams['LINK_IBLOCK_ID'] = '24'?>
                <?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/news.detail.products_'.$list_view.'.php');?>
            </div>
        </div>
    <?endif?>
	<?if ($arParams["SHOW_SERVICES_BLOCK"]=="Y"):?>
		<div class="ask_big_block">
			<div class="ask_btn_block">
				<a class="button vbig_btn wides services_btn" data-title="<?=$arResult["NAME"];?>"><span><?=GetMessage("SERVICES_CALL")?></span></a>
			</div>
			<div class="description">
				<div class="desc">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/services_block_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ASK_QUESTION_DETAIL_TEXT"), ));?>
				</div>
				<div class="price services">
					<div class="price_new">
						<?=$templateData['PRICE'];?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?endif;?>
<?if ($arParams["SHOW_BACK_LINK"]=="Y"):?>
	<?$refer=$_SERVER['HTTP_REFERER'];
	if (strpos($refer, $arResult["LIST_PAGE_URL"])!==false) {?>
		<div class="back"><a class="back" href="javascript:history.back();"><span><?=GetMessage("BACK");?></span></a></div>
	<?}else{?>
		<div class="back"><a class="back" href="<?=$arResult["LIST_PAGE_URL"]?>"><span><?=GetMessage("BACK");?></span></a></div>
	<?}?>
<?endif;?>