<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Контакты");
$APPLICATION->SetPageProperty("keywords", "Контакты");
$APPLICATION->SetPageProperty("title", "Контакты");
$APPLICATION->SetTitle("Контакты");
?><div class="container-content">
	<div class="contacts_map">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view",
	"map",
	Array(
		"API_KEY" => "AIzaSyDYyQfVZSHz17oRIHATSDFjhhKNhyZUPOs",
		"COMPONENT_TEMPLATE" => "map",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLS" => array(),
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:52.32054950569427;s:10:\"google_lon\";d:104.2332278372021;s:12:\"google_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:28:\"Стройлогистика\";s:3:\"LON\";d:104.2303955555;s:3:\"LAT\";d:52.320277981901;}}}",
		"MAP_HEIGHT" => "400",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",),
		"ZOOM_BLOCK" => array("POSITION"=>"right center",)
	)
);?>
	</div>
	<div class="wrapper_inner wrapper_inner_white">
		<div class="contacts_left">
			<div class="store_description">
				<div class="store_property">
					<div class="title">
						 Адрес
					</div>
					<div class="value">
						 <?$APPLICATION->IncludeFile(SITE_DIR."include/address.php", Array(), Array("MODE" => "html", "NAME" => "Адрес"));?>
					</div>
				</div>
				<div class="store_property">
					<div class="title">
						 Телефон
					</div>
					<div class="value">
 <a style="color:#00adee;" title="Розничным клиентам" href="#">Розничным клиентам:</a>
						<!--<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => "Телефон"));?>--> <a href="tel:+7(3952)280-900" rel="nofollow" onclick="sendSocEvent('clickphone','tel:+7(3952)280-900');return true;">8(3952)280-900</a><br>
 <a style="color:#00adee;" title="Торговым организациям" href="/info/torgovym-organizaciyam/">Торговым организациям:</a> <a href="tel:+7(3952)260-960" rel="nofollow" onclick="sendSocEvent('clickphone','tel:+7(3952)260-960');return true;">8(3952)260-960</a><br>
 <a style="color:#00adee;" title="Строительным организациям" href="/info/stroitelnym-organizaciyam/">Строительным организациям:</a> <a href="tel:+7(3952)260-990" rel="nofollow" onclick="sendSocEvent('clickphone','tel:+7(3952)260-990');return true;">8(3952)260-990</a>
					</div>
				</div>
				<div class="store_property">
					<div class="title">
						 Email
					</div>
					<div class="value">
						 <?$APPLICATION->IncludeFile(SITE_DIR."include/email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?>
					</div>
				</div>
				<div class="store_property">
					<div class="title">
						 Режим работы
					</div>
					<div class="value">
						 <?$APPLICATION->IncludeFile(SITE_DIR."include/schedule.php", Array(), Array("MODE" => "html", "NAME" => "Время работы"));?>
					</div>
				</div>
			</div>
		</div>
		<div class="contacts_right">
			<blockquote>
				 <?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?>
			</blockquote>
			 <?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-feedback-block");?> <?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"inline",
	Array(
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "?send=ok",
		"USE_EXTENDED_ERRORS" => "Y",
		"VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
		"WEB_FORM_ID" => "3"
	)
);?> <?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
		</div>
	</div>
	<div class="clearboth">
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>