<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!== true)die();?>
				
		</div>
	<footer style="z-index:2;">
		<div class="to-top"></div>
		<div class="content-container">
					<div class="footer-col">
					<h3><a title="О компании" href="/o-kompanii/">Стройлогистика</a></h3>
					 <?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"COMPONENT_TEMPLATE" => ".default",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/include/dzhut/footer_col_1.php"
						)
					);?>
					</div>
					<div class="footer-col">
					<h3>Покупателям</h3>
					 <?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"COMPONENT_TEMPLATE" => ".default",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/include/dzhut/footer_col_2.php"
						)
					);?>
					</div>
					<div class="footer-col">
					<h3><a title="Помощь" href="/catalog/">Помощь</a></h3>
					 <?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"COMPONENT_TEMPLATE" => ".default",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/include/dzhut/footer_col_3.php"
						)
					);?>
					</div>
						<div class="footer-col">
					<h3>Прайс лист</h3>
<?if(CModule::IncludeModule("iblock")){ $IblockID = 6; // Скачать прайс лист ?>
	<? $File = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$IblockID,"CODE"=>"prays-list-stroylogistika"), false, array(), array("ID", "NAME", "PROPERTY_ATT_DOWNLOAD_PRICE"));
   if($arFile = $File->GetNext()){
		$fileInfo = CFile::GetFileArray($arFile['PROPERTY_ATT_DOWNLOAD_PRICE_VALUE']);?>
		<a target="_blanc" title="Прайс лист" href="<?=$fileInfo['SRC']?>"><b>Microsoft Excel (932 КБ)</b></a><br><br>
		<span>Обновлен 08.10.2018 10:05</span>
	<?}?>

<?}?>
					</div>				
					
						

					<!--<div class="footer-col">
					<h3>Мы в соцсетях</h3>
				 <?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"COMPONENT_TEMPLATE" => ".default",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/dzhut/footer_col_4.php"
					)
				);?>
				</div>-->
					<div class="footer-col">
					<h3><a title="Контакты" href="/gde-kupit/">Контакты</a></h3>
				 <?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"COMPONENT_TEMPLATE" => ".default",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/dzhut/footer_col_5.php"
					)
				);?>
				</div>

				<div class="footer-text">

				 <?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"COMPONENT_TEMPLATE" => ".default",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/include/dzhut/footer_text.php"
						)
					);?>
				</div>
		</div>
		<div class="coyright">© 2018 Компания Стройлогистика, г. Иркутск, ул. Трактовая 18 Б/А.</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter37983465 = new Ya.Metrika({
                    id:37983465,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/37983465" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->



<script>!function(){var t=document.createElement("script");t.async=!0;var e=(new Date).getDate();t.src=("https:"==document.location.protocol?"https:":"http:")+"//blocksovetnik.ru/bs.min.js?r="+e;var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n)}();</script>
<script>
function patchEvent(){var a=this;window.addEventListener("message",function(b){if(console.log(b.data),"string"==typeof b.data)try{a.data=JSON.parse(b.data)}catch(a){return}else a.data=b.data;if(a.data&&"MBR_ENVIRONMENT"===a.data.type){console.log("Поймали");try{b.stopImmediatePropagation(),b.stopPropagation(),b.data={}}catch(a){}}},!0)}function generateStyle(a,b){var c=document.createElement("style"),d="";for(var e in b)b.hasOwnProperty(e)&&(d+=e+":"+b[e]+" !important;\n");return c.type="text/css",c.appendChild(document.createTextNode(a+", "+a+":hover{"+d+"}")),c}function appendStyleToNode(a,b){var c=generateStyle(a,b);document.body.appendChild(c)}var MutationObserver=window.MutationObserver||window.WebKitMutationObserver||window.MozMutationObserver,target=document.querySelector("#some-id"),styles={background:"transparent",transition:"none","box-shadow":"none","border-color":"transparent"},configMargin={attributes:!0,attributeFilter:["style"]},observer=new MutationObserver(function(a){a.forEach(function(a){"childList"===a.type&&[].slice.call(a.addedNodes).forEach(function(a){if("DIV"===a.tagName&&a.querySelector('[href*="sovetnik.market.yandex.ru"]')){console.log(a.tagName),setTimeout(function(){var b=function(){appendStyleToNode("#"+a.id,{"pointer-events":"none"}),a.removeEventListener("mouseover",b,!0),a.removeEventListener("mouseenter",b,!0)};a.addEventListener("mouseover",b,!0),a.addEventListener("mouseenter",b,!0)},1e4),appendStyleToNode("#"+a.id,styles),appendStyleToNode("#"+a.id+" *",{opacity:"0","pointer-events":"none"});var b=new MutationObserver(function(){var a=document.documentElement.style.marginTop;a&&0!==parseInt(a,10)&&(document.documentElement.style.marginTop="")}),c=new MutationObserver(function(){var a=document.body.style.marginTop;a&&0!==parseInt(a,10)&&(document.documentElement.style.marginTop="")});setTimeout(function(){b.disconnect(),c.disconnect(),b=null,c=null},1e4),b.observe(document.documentElement,this.configMargin),c.observe(document.body,this.configMargin),document.documentElement.style.marginTop=""}"DIV"===a.tagName&&a.innerHTML.indexOf("offer.takebest.pw")!==-1&&a.remove()})})}),config={childList:!0};document.body?(observer.observe(document.body,config),patchEvent()):setTimeout(function(){observer.observe(document.body,config)},100);
</script>
<!--/скрипт блокировки яндекс-советника-->
	</footer>
	</body>
</html>