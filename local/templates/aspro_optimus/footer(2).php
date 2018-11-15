<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!COptimus::IsMainPage()):?>
                                   </div> <?// .container?>
							<?endif;?>
						</div>
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()):?>
						</div> <?// .right_block?>
					<?endif;?>
				</div> <?// .wrapper_inner?>				
			</div> <?// #content?>
		</div><?// .wrapper?>
		<footer id="footer">
			<div class="footer_inner <?=strtolower($TEMPLATE_OPTIONS["BGCOLOR_THEME_FOOTER_SIDE"]["CURRENT_VALUE"]);?>">

				<?if($APPLICATION->GetProperty("viewed_show")=="Y" || defined("ERROR_404")):?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"COMPONENT_TEMPLATE" => ".default",
		"PATH" => SITE_DIR."include/footer/comp_viewed.php",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_TEMPLATE" => "standard.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
				<?endif;?>
				<div class="wrapper_inner" style="background: #e4e4e4;">
					<div class="footer_bottom_inner">

						<!--<div class="left_block">
							<?/*$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/copyright.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);*/?>
							<div id="bx-composite-banner"></div>
						</div>-->

						<div class="right_block strlog-right-block">
							<div class="middle">
								<div class="rows_block">
									<div class="item_block col-75 menus">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "3600000",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="rows_block">
											<div class="item_block col-5">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-5">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-5">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
                                            <div class="item_block col-5">
                                                <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                    array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR."include/footer/copyright.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "standard.php"
                                                ),
                                                false
                                                );?>
											</div>
                                            <div class="item_block col-5">
                                                <?if(CModule::IncludeModule("iblock")):?>
                                                    <?$IblockID = 26;?>
                                                    <?$File = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$IblockID,"CODE"=>"prays-list-stroylogistika"), false, array(), array("ID", "NAME", "PROPERTY_ATT_DOWNLOAD_PRICE"));?>
                                                    <?if($arFile = $File->GetNext()):?>
                                                        <?$fileInfo = CFile::GetFileArray($arFile['PROPERTY_ATT_DOWNLOAD_PRICE_VALUE']);?>
														<?$mbSize = round($fileInfo['FILE_SIZE'] / 1024 / 1024,2);?>
														<div class="menu_item pricef"><a href="/help/payment/" class="dark_link">Прайс лист</a></div>
                                                        <div class="f_price_text">
                                                            <a target="_blanc" title="Прайс лист" href="<?=$fileInfo['SRC']?>">
                                                                <span class="f_price_title">Microsoft Excel </span>
																<span class="f_price_size"> (<?=$mbSize?>  Мб)<!--date('d.m.Y') (07.05.2018 12:35)--></span>
                                                            </a>
															<span class="date-info-price">Обновлен <?=date('d.m.Y H:i',filemtime($_SERVER['DOCUMENT_ROOT'].$fileInfo['SRC']))?></span>
                                                        </div>
                                                    <? endif;?>
                                                <?endif;?>
                                            </div>
										</div>
									</div>
									<div class="item_block col-5 soc">
										<div class="soc_wrapper">
											<div class="phones">
												<div class="phone_block">
													<span class="phone_wrap">
														<span class="icons fa fa-phone"></span>
														<span>
															<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
																array(
																	"COMPONENT_TEMPLATE" => ".default",
																	"PATH" => SITE_DIR."include/phone.php",
																	"AREA_FILE_SHOW" => "file",
																	"AREA_FILE_SUFFIX" => "",
																	"AREA_FILE_RECURSIVE" => "Y",
																	"EDIT_TEMPLATE" => "standard.php"
																),
																false
															);?>
														</span>
													</span>
													<span class="order_wrap_btn">
														<span class="callback_btn"><?=GetMessage('CALLBACK')?></span>
													</span>
												</div>
											</div>
											<div class="social_wrapper">
												<div class="social">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"COMPONENT_TEMPLATE" => ".default",
		"PATH" => SITE_DIR."include/footer/social.info.optimus.default.php",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_TEMPLATE" => "standard.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="mobile_copy">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"PATH" => SITE_DIR."include/footer/copyright.php",
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "",
								"AREA_FILE_RECURSIVE" => "Y",
								"EDIT_TEMPLATE" => "standard.php"
							),
							false
						);?>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>
			</div>
		</footer>
		<?
		COptimus::setFooterTitle();
		COptimus::showFooterBasket();
		?>
	</body>
</html>