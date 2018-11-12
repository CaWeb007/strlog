<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<pre>
<?print_r($arResult['PRICES']);?>
</pre>

<?
global $arrFilter;
if($_GET['saler'])
{
	$arrSaler[$_GET['saler']] = $_GET['saler'];
	$arrFilter = array('catalog_PRICE_1' => $arrSaler);
}
else
{
	$arrFilter = array("catalog_PRICE_1" => $arResult['PRICES']);
}
?>

<?$prices = $arResult['PRICES'];?>
<div class="salers-titles-wrapper">
	<?foreach($prices as $price):?>
	<div class="salers-title-item">
		<?if(!empty($arrSaler[$price])):?>
		<noindex><a href="/salers/" class="salers-link-active"><?=$price;?><ruble><span class="text"></span></ruble></a></noindex>
		<?else:?>
		<noindex><a href="?saler=<?=$price?>"><?=$price;?><ruble><span class="text"></span></ruble></a></noindex>
		<?endif;?>
	</div>
	<?endforeach;?>
	<div class="salers-title-item">
		<noindex><a href="/salers/">Сбросить</a></noindex>
	</div>
</div>