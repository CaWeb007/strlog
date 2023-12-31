<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Помощь");
?><div class="container-content"><p>
	 Уважаемые клиенты! Мы стараемся сделать условия работы с нами максимально комфортными для вас.
</p>
<p>
	 Здесь вы найдете полезные разделы с подробной информацией, которые вам понадобятся при оформлении заказа. 
</p>
<p>
	 Вы можете воспользоваться сервисами:
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
<!--
<ul>
	<li>Как заказать – подробная информация о способах заказа товаров.
</li>
<li>
	 Как оплатить – подробная информация о способах оплаты.
</li>
<li>
	 Доставка – информация о видах, условиях и правилах работы доставки.
</li>
<li>
	 Обмен и возврат - Здесь вы найдете подробную информацию о сроках и условиях возврата, если по каким-либо причинам он вам не подошел.
</li>
<li>
	 Статьи – полезные статьи и различные информационные материалы о строительстве, строительных и отделочных материалах.
</li>
<li>
	 Вопрос-ответ – раздел, где вы сможете быстро найти готовые ответы на часто задаваемые вопросы.
</li>
</ul>
-->
<p>
	Вы можете задать любой вопрос, связанный с покупкой в нашем Интернет-магазине: как покрасить, какой утеплитель лучше, в чем разница ДВП и ДСП и многое другое.
</p>
<p>
	 Если у вас остались вопросы по какому-либо разделу, пожалуйста, позвоните по тел: (3952) 280-900
</p>
<p>
	 Наши специалисты всегда помогут и проконсультируют по любому вопросу.
</p>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>