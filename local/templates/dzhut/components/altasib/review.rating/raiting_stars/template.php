<?
#################################################
#        Company developer: ALTASIB
#        Developer: Evgeniy Pedan
#        Site: http://www.altasib.ru
#        E-mail: dev@altasib.ru
#        Copyright (c) 2006-2011 ALTASIB
#################################################
?>
<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$num = round($arResult,0,PHP_ROUND_HALF_DOWN);

$OneStar = 'Оценка товара: 1 из 5';
$TwoStar = 'Оценка товара: 2 из 5';
$ThreeStar = 'Оценка товара: 3 из 5';
$FoureStar = 'Оценка товара: 4 из 5';
$FiveStar = 'Оценка товара: 5 из 5';
$textRaiting = '';
$CountLike = 'Голосов: <span id="count-liker"></span>';

switch($num){
	case 1:
		$textRaiting = $OneStar;
	break;	
	case 2:
		$textRaiting = $TwoStar;
	break;
	case 3:
		$textRaiting = $ThreeStar;
	break;
	case 4:
		$textRaiting = $FoureStar;
	break;
	case 5:
		$textRaiting = $FiveStar;
	break;
}

?>
<?if($arResult):$arParams["SHOW_MESS"] = true;?>
<script>
	
	var review_last_rating = 0;
    var stop_draw = false;
    function DrawRatingStar(num,out,star,substar)
    {
        if((out && review_last_rating>0) || stop_draw)
            return;
            
        for(i=1;i<6;i++)
        {
            if(i<=num)
            {
                BX("review_rating_"+i).className='alx_vote_item_a';
            }
            else
            {
                BX("review_rating_"+i).className='alx_vote_item_na';
            }
            
            if(out && i==star+1)
                BX("review_rating_"+i).className='alx_vote_item_na alx_vote_item_a'+substar;
        }

   
    }

	function ReviewSetRating(num)
	{
        review_last_rating = num;
        oData = {"RATING" : num,"ACTION" : "SET_RATING","sessid" : '<?=bitrix_sessid();?>',"ELEMENT_ID": <?=$arParams["ELEMENT_ID"];?>};
        BX.ajax.post(window.location.href,oData,function (res) 
        {
            stop_draw = true;
            eval(res);
        });       
	}
</script>
<?$arStar = explode(".",$arResult);?><? $Star = $arStar[0]; $subStar = isset($arStar[1]) ? $arStar[1] : 0;?>
<div class="alx_reviews_elem_vote" onmouseout="DrawRatingStar(<?=$Star;?>,true,<?=$Star?>,<?=$subStar?>);">
<?if($arParams["SHOW_TITLE"]):?>
	<div class="alx_vote_value">
		<b><? if(strlen($arParams["TITLE_TEXT"])>0): echo $arParams["TITLE_TEXT"]; else: echo GetMessage("ALTASIB_REVIEW_RATING_ITEM"); endif;?></b>
	</div>
<?endif;?>   

 
	<div class="alx_vote_items">
		<?for($i=1;$i<=5;$i++){?>
		<div id="review_rating_<?=$i;?>" 
            <?if($arParams["ALLOW_VOTE"]):?>
                onmouseover="DrawRatingStar(<?=$i;?>,false)" 
                onmouseout="DrawRatingStar(<?=$i;?>,false)" 
                onclick="ReviewSetRating(<?=$i;?>)"<?endif;?> 
                class="<?if($i<=$Star):?>alx_vote_item_a<?else:?>alx_vote_item_na <?if($i==$Star+1):?>alx_vote_item_a<?=$subStar?><?endif?><?endif?>">
                <img src="<?=$templateFolder?>/images/spacer.gif" alt="<?=$arResult?>" title="<?=$arResult?>" border="0" />
        </div>
		<?}?>
	</div>
	<?
	global $APPLICATION;
	if($APPLICATION->GetCurPage() != "/"):?>
		<div class="review_rating_txt" id="review_rating_txt"><?=$textRaiting?></div>
		<div class="review_rating_txt" id="review_rating_txt"><?=$CountLike?></div>

	<?endif;?>
</div>
<?endif;?>
<script>
$(document).ready(function(){
	$("#count-liker").html($('.alx_reviews_list .alx_reviews_item').length);
})
</script>