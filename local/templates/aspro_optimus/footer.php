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
<?/*?>
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
							<div id="bx-composite-banner"></div>
						</div>-->

						<div class="right_block strlog-right-block">
							<div class="middle">
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
<?*/?>
		<?
		COptimus::setFooterTitle();
		COptimus::showFooterBasket();
		?>
        <?/*if($USER->IsAdmin()):?>
            <script>
                (function(w,d,u){
                    var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                    var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                })(window,document,'https://crm.strlog.ru/upload/crm/site_button/loader_12_eo5c95.js');
            </script>
        <?else:?>
            <script>
                (function(w,d,u){
                    var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                    var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                })(window,document,'https://crm.strlog.ru/upload/crm/site_button/loader_3_lrizq0.js');
            </script>
        <?endif*/?>
        <div id="bx_ord_popup" class="ord-popup">
            <div class="ord-head">
                <div class="ord-info-logo">
                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.5c-7.14 0-9.5-2.353-9.5-9.47C2.5 4.912 4.86 2.5 12 2.5c7.141 0 9.5 2.412 9.5 9.53 0 7.117-2.359 9.47-9.5 9.47" stroke="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M13 6h-2v2h2V6zm0 4h-2v8h2v-8z" fill="currentColor"></path></svg>
                </div>
                <div class="ord-text">
                    <div class="ord-text-head">
                        Рекламодатель
                    </div>
                    <div class="ord-text-footer">
                        ООО "Альянс"
                    </div>
                </div>
            </div>
            <div class="ord-separator"></div>
            <div class="copy-ord-link">
                <div class="copy-ord-link-icon">
                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 18.5h-3V6L7 3.5h9.5v2m.5 15H7.5V8L10 5.5h9.5V18L17 20.5z" stroke="currentColor"></path></svg>
                </div>
                <div class="copy-ord-link-text">
                    Скопировать ссылку
                </div>
            </div>
        </div>
        <div id="fullscreenButtonsControl">
            <div class="buttons">
                <button onclick="history.back();" class="left-button">
                    <div class="button-text">Назад</div>
                </button>
                <button onclick="history.forward();" class="right-button">
                    <div class="button-text">Вперед</div>
                </button>
            </div>
        </div>
	</body>
</html>