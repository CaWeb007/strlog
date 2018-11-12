<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

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
</script>
<?$arStar = explode(".",$arResult);?><? $Star = $arStar[0]; $subStar = isset($arStar[1]) ? $arStar[1] : 0;?>

<div class="alx_reviews_elem_vote"> 
	<div class="alx_vote_items">
		<?for($i=1;$i<=5;$i++){?>
		<div id="review_rating_<?=$i;?>" 
           
                class="<?if($i<=$Star):?>alx_vote_item_a<?else:?>alx_vote_item_na <?if($i==$Star+1):?>alx_vote_item_a<?=$subStar?><?endif?><?endif?>">
                <img src="<?=$templateFolder?>/images/spacer.gif" alt="<?=$arResult?>" title="<?=$arResult?>" border="0" />
        </div>
		<?}?>
	</div>

</div>
<?endif;?>
