<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){
	global $TEMPLATE_OPTIONS,$APPLICATION;?>
<ul class="menu top menu_top_block catalogfirst<?if($APPLICATION->GetCurPage() == "/"):?> main-catalogfirst<?endif?>">
		<?foreach( $arResult as $key => $arItem ){?>
			<li style="background: none;" class="catalog icons_fa <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current" : "");?>">
				<!--<a class="<?/*=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]*/?></a>--><!--/Старое название каталога-->
				<a ontouchstart="" class="strlog-catalog-menu-parent parent desktop-menu" href="<?//=$arItem["LINK"]?>/"><img src="/images/new_logo.png" alt="" title="" /></a>
				<a ontouchstart="" class="mobile-menu" href="<?//=$arItem["LINK"]?>/"></a>
				<?if($arItem["CHILD"]){?>
					<ul class="dropdown">
						<li class="search-mobile">
							<div class="search">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
										"AREA_FILE_SHOW" => "file",
										"AREA_FILE_SUFFIX" => "",
										"AREA_FILE_RECURSIVE" => "Y",
										"EDIT_TEMPLATE" => "standard.php"
									),
									false
								);?>
					   </div>
						</li>
						<?foreach($arItem["CHILD"] as $arChildItem){?>
							<li class="full <?=($arChildItem["CHILD"] ? "has-child" : "");?> <?if($arChildItem["SELECTED"]){?> current opened <?}?> m_<?=strtolower($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]);?> v_<?=strtolower($TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"]);?>">
								<a class="icons_fa <?=($arChildItem["CHILD"] ? "parent" : "");?>" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><?=$arChildItem["NAME"];?><div class="toggle_block"></div></a>
								<?if($arChildItem["CHILD"]){?>
									<ul class="dropdown">
										<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
											<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
												<?/*if($arChildItem1["IMAGES"] && $TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"] != 'BOTTOM'){?>
													<span class="image"><a href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem1["IMAGES"]["src"];?>" alt="<?=$arChildItem1["NAME"];?>"/></a></span>
												<?}*/?>
												<a class="section dark_link" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_1"><?=$arChildItem1["COUNT"];?></span></span></a>
												<?if($arChildItem1["CHILD"]){?>
													<ul class="dropdown">
														<?foreach($arChildItem1["CHILD"] as $arChildItem2){?>
															<li class="menu_item <?if($arChildItem2["SELECTED"]){?> current <?}?>">
																<a class="section1" href="<?=$arChildItem2["SECTION_PAGE_URL"];?>"><span><?=$arChildItem2["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem2["COUNT"];?></span></span></a>
															</li>
														<?}?>
													</ul>
												<?}?>
												<div class="clearfix"></div>
											</li>
										<?}?>
									</ul>
								<?}?>
							</li>
						<?}?>
					</ul>
				<?}?>
			</li>
		<?}?>
	</ul>
<?}?>