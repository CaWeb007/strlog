<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
CJSCore::Init(array("popup"));
$curPage = $APPLICATION->GetCurPage();
?><!doctype html>
<html>
	<head>
		<title><?$APPLICATION->ShowTitle();?></title>
		<meta name="robots" content="noodp"/>
		<meta name="viewport" content="width=1210">
		<meta name='yandex-verification' content='65ad694d7e0e594c' />
		<meta name="google-site-verification" content="6oO_6wOeWpC2PxXS_V50t1-0BI8UY1IzsI00OiYXyZ4" />
		<?$APPLICATION->ShowHead();?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/jquery.fancybox.css")?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/slick/slick.css")?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/normalize.css")?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/template_custom.css")?>
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/select/select-theme-default.css")?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery-1.12.0.min.js");?>


		<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
		<script type="text/javascript">
			BX.ready(function(){
				DG.then(function () {
		    	var map,
			    	myDivIcon;

		        map = DG.map('map', {
		            center: [52.3061605, 104.2470145],
		            zoom: 13,

		        });

		        myIcon = DG.divIcon({
		        	iconSize:  [340, 203],
		        	iconAnchor: [170, 203],
		        	html: '<div class="map-icon"><div class="icon"></div><div class="description"></div></div>',
		        });

				/*DG.marker([52.289243, 104.282961], {
		        	icon: myIcon
				}).addTo(map);*/

				DG.marker([52.320093, 104.230497], {
		        	icon: myIcon
				}).addTo(map);

		        map.scrollWheelZoom.disable();
		    });
			})
		</script>

		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/velocity.min.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.fancybox.js");?>
		<?//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.elevatezoom.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/slick/slick.min.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/inputmask.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/inputmask.phone.extensions.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.inputmask.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/select/tether.min.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/select/select.min.js");?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/script.js");?>


<!--[if lt IE 8]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js" type="text/javascript"></script>
<![endif]-->


<?/*Canonical link*/?>
<?$canonicalLink = $curPage;?>
<?$canonicalFilter = 'filter';?>
<?$canonicalApply = 'apply';?>
<?$canonicalClear = 'clear';?>
<?$findCanonicalFilter = stristr($canonicalLink, $canonicalFilter);?>
<?$findCanonicalApply = stristr($canonicalLink, $canonicalApply);?>
<?$findCanonicalClear = stristr($canonicalLink, $canonicalClear);?>
<?if($findCanonicalFilter || $findCanonicalApply || $findCanonicalClear):?>
<?$canonicalLink = str_replace(array($findCanonicalFilter, $findCanonicalApply, $findCanonicalClear), '', $canonicalLink);?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink);?>
<?/*Пагинация*/?>
<?elseif($_GET['PAGEN_1']):?>
<?$canonicalPagen = $_GET['PAGEN_1'];?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink.'?PAGEN_'.$canonicalPagen.'='.$canonicalPagen);?>
<?elseif($_GET['PAGEN_2']):?>
<?$canonicalPagen = $_GET['PAGEN_2'];?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink.'?PAGEN_'.$canonicalPagen.'='.$canonicalPagen);?>
<?elseif($_GET['PAGEN_3']):?>
<?$canonicalPagen = $_GET['PAGEN_3'];?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink.'?PAGEN_'.$canonicalPagen.'='.$canonicalPagen);?>
<?elseif($_GET['PAGEN_4']):?>
<?$canonicalPagen = $_GET['PAGEN_4'];?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink.'?PAGEN_'.$canonicalPagen.'='.$canonicalPagen);?>
<?elseif($_GET['PAGEN_5']):?>
<?$canonicalPagen = $_GET['PAGEN_5'];?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink.'?PAGEN_'.$canonicalPagen.'='.$canonicalPagen);?>
<?/*/Пагинация*/?>
<?else:?>
<?$APPLICATION->SetPageProperty("canonical", $canonicalLink);?>
<?endif;?>
<?/*/Canonical link*/?>

<!-- Google Tag Manager --
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NP3Q8H5');</script>
<!-- End Google Tag Manager -->

	</head>
<body>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script src="https://www.googletagmanager.com/gtag/js?id=UA-121466226-1" async ></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-121466226-1');
</script>

<!-- Google Tag Manager (noscript) --
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NP3Q8H5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?$APPLICATION->ShowPanel();?>
<!-- HEADER END -->

<div class="workarea">

<!--LEFT SIDE BAR-->
