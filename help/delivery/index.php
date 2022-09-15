<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Условия доставки");
$APPLICATION->SetPageProperty("keywords", "Условия доставки");
$APPLICATION->SetTitle("Условия доставки");
?>
<div class="container-content">
	Компания «Стройлогистика» организует доставку товаров по всей РФ.<br>
	<br>
	Для оформления доставки необходимо предоставить следующую информацию:<br>
	<br>
	&nbsp;- контактное лицо;<br>
	&nbsp;- контактный номер телефона;<br>
	&nbsp;- адрес доставки.<br>
	<br>
	Правила работы службы доставки:<br>
	<br>
	&nbsp; &nbsp;Доставка материалов осуществляется транспортом компании «Стройлогистика»&nbsp;<br>
	&nbsp;- доставка товара с 09:00 до 18:00 по будням, в субботу с 10:00 до 15:00;<br>
	&nbsp;- водитель ТС, прибывающий на адрес разгрузки, доезжает ровно до того места, до которого есть возможность проезда;<br>
	&nbsp;- не нарушая правила ГИБДД;<br>
	&nbsp;- водитель не совершает разгрузку ТС;<br>
	- разгрузка материала до 30 минут - бесплатно, свыше 30 минут до часа– 500 рублей, свыше часа – 650 руб;
	<p>
		&nbsp;- ожидание покупателя по адресу доставки 15 минут, свыше за каждые 10 минут простоя-100 рублей.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	</p>
	<p>
		Доставка линолеума только после оплаты, так как данный товар в нарезке возврату и обмену не подлежит.&nbsp;
	</p>
	<a href="https://стройлогистика.рф/upload/medialibrary/e23/e235a649f6bfc920d96d270654ffb172.jpg"><img width="205" alt="69d8dc55-e3c6-4dd6-bafa-67f63bd60e44.jpg" src="/upload/medialibrary/e23/e235a649f6bfc920d96d270654ffb172.jpg" height="154" title="Оперативная доставка СТРОЙЛОГИСТИКА"></a>&nbsp; &nbsp;&nbsp;<a href="https://стройлогистика.рф/upload/medialibrary/6d2/6d2adadb7bfcaa7da9b0f6451f6902d3.jpg"><img width="205" alt="747bc068-effc-4f46-bab7-538405668c05.jpg" src="/upload/medialibrary/6d2/6d2adadb7bfcaa7da9b0f6451f6902d3.jpg" height="154" title="Оперативная доставка СТРОЙЛОГИСТИКА"></a>&nbsp; &nbsp;<a href="https://стройлогистика.рф/upload/medialibrary/bab/bab9b5107903d281d8dd44533a6d28fb.jpg"><img width="205" alt="561ed2d9-2173-464c-8cd3-93704357ec8a.jpg" src="/upload/medialibrary/bab/bab9b5107903d281d8dd44533a6d28fb.jpg" height="154" title="Оперативная доставка СТРОЙЛОГИСТИКА"></a>
	<br>
	<br>
	<div style="position:relative;overflow:hidden; width: 100%"><a href="https://yandex.ru/maps/63/irkutsk/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Иркутск</a><a href="https://yandex.ru/maps/63/irkutsk/?ll=104.290012%2C52.298668&mode=usermaps&source=constructorLink&um=constructor%3Ab23f58b87c6f2d9ede700275566702c0117f5b1c6635d5bd42a83a8ffeff1efe&utm_medium=mapframe&utm_source=maps&z=11" style="color:#eee;font-size:12px;position:absolute;top:14px;">Яндекс Карты — транспорт, навигация, поиск мест</a><iframe src="https://yandex.ru/map-widget/v1/-/CCUB7Ltu3B" width="100%" height="500" frameborder="0" allowfullscreen="true" style="position:relative;"></iframe></div>
	<br>
	<strong>Виды и условия доставки*</strong>:
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH" => SITE_DIR."include/inc_delivery_page.php",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_TEMPLATE" => "standard.php",
	),
		false
	);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>