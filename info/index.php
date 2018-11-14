<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Покупателям");
$APPLICATION->SetPageProperty("keywords", "Покупателям");
$APPLICATION->SetPageProperty("description", "Покупателям");
$APPLICATION->SetTitle("Покупателям");
?><div class="container-content">
<p>
	 Уважаемые клиенты!
</p>
<p>
	 В данном разделе вы найдете актуальную информацию, которая поможет вам при совершении покупок в нашем Интернет-магазине.
</p>
<p>
	 Собственное производство и сотрудничество с лидирующими заводами-производителями напрямую, обеспечивает обширный ассортимент продукции, что в свою очередь позволяет нашей компании быть ведущим дистрибьютором для большинства компаний Иркутска, иркутской области и региональных центров СФО и ДВФО. Мы постоянно заботимся о качестве товаров и держим низкий уровень цен, чтобы вы могли совершать только выгодные покупки. Мы строго следим за качеством нашей продукции, которая проходит несколько этапов контроля прежде, чем непосредственно поступит в продажу.
</p>
<p>
	В нашем Интернет- магазине вы можете заказ товар по звонку, с возможностью оплаты наличным и безналичным способами.
</p>
<p>
	 Мы осуществляем доставку заказанных товаров «до двери».
</p>
<p>
	В данном разделе сайта вы найдете полезную информацию о новостях, акциях, бонусной программе и спецпредложениях, информацию для торговых и строительных организаций.
</p>
<p>
	У нас действует программа лояльности, используя которую вы можете совершать выгодные покупки и при этом накапливать бонусы, для того, чтобы оплатить ими следующую покупку.
</p>
<p>
	Мы регулярно  предлагаем вам интересные акции, распродажи и спецпредложения.
</p>
<p>
	Информацию о новинках, товарах с уценкой и других дополнительных предложениях нашей компании вы можете узнать в соответствующих разделах сайта.
</p>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"tree", 
	array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "2",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "tree",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"MENU_THEME" => "site"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
<p>
	Удачных покупок!
</p>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>