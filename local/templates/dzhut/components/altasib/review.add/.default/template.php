<?
#################################################
#        Company developer: ALTASIB
#        Developer: Evgeniy Pedan
#        Site: http://www.altasib.ru
#        E-mail: dev@altasib.ru
#        Copyright (c) 2006-2013 ALTASIB
#################################################
?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$isHidden = true;
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
$isHidden = false;
}
if($arParams["NOT_HIDE_FORM"] && $isHidden)
    $isHidden = false;

if(isset($_SESSION["REVIEW_ADD_OK"]) && $_SESSION["REVIEW_ADD_OK"])
{
	ShowNote($arParams["MESSAGE_OK"]);
	unset($_SESSION["REVIEW_ADD_OK"]);
    $isHidden = true;
}
?>
<div class="review-forms">
<?if($isHidden):?><div class="alx_add_reviews_a addReview" id="review_show_form"><a href="javascript:void(0)" onclick="ShowReviewForm();"><?echo strlen($arParams["ADD_TITLE"]) ==0 ? GetMessage("ALTASIB_TP_ADD") : $arParams["ADD_TITLE"]?></a></div><?endif;?>
<div id="review_add_form" style="<?if($isHidden):?>display:none;<?endif;?>">
<div id="wiatComment"></div>
<form name="review_add" id="review_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
	<?=bitrix_sessid_post()?>
    <input type="hidden" value="<?=$arParams["ELEMENT_ID"];?>" name="ELEMENT_ID">
	<input type="hidden" value="<?=$arResult["arMessage"]["RATING"];?>" name="RATING" id="RATING">
	<div class="alx_reviews_block_border">&nbsp;</div>
<div class="alx_reviews_block">
	<div class="alx_reviews_form">
        <?if(!$USER->IsAuthorized()):?>
		
    		<div class="alx_reviews_form_item_pole alx_reviews_form_poles_small">
    			
    			<div class="alx_reviews_form_inputtext_bg">
					<input type="text" name="AUTHOR_NAME" value="<?=$arResult["arMessage"]["AUTHOR_NAME"];?>" placeholder="Ваше Имя"/>
				</div>
			
    		</div>
			
    		<div class="alx_reviews_form_item_pole alx_reviews_form_poles_small">
    			
    			<div class="alx_reviews_form_inputtext_bg">
					<!--<input type="text" name="AUTHOR_EMAIL" value="<?=$arResult["arMessage"]["AUTHOR_EMAIL"];?>" placeholder="Ваш Email"/>-->
					<input type="hidden" name="AUTHOR_EMAIL" value="unregisterd@strlog.ru" />
				</div>
				
    		</div>
			
        <?endif;?>
        
        <?if($arParams["ALLOW_TITLE"]):?>
    		<div class="alx_reviews_form_item_pole alx_reviews_form_item_pole_last">
    			<div class="alx_reviews_form_pole_name"><span class="requred_txt">*</span> <?=GetMessage("ALTASIB_TP_TITLE")?>:</div>
    			<div class="alx_reviews_form_inputtext_bg"><div class="alx_reviews_form_inputtext_bg_arr"></div><input type="text" name="TITLE" value="<?=$arResult["arMessage"]["TITLE"];?>" /></div>
    		</div>
            <div class="alx_clear_block">&nbsp;</div>
		<?endif;?>
		
        <? // Не вывожу достоинства и недостатки
		if($arParams["COMMENTS_MODE"]):?>
        <div class="alx_reviews_form_item_pole_textarea dost">
			<div class="alx_reviews_form_pole_name"><span class="requred_txt">*</span> <?=GetMessage("ALTASIB_TP_PLUS")?>:</div>
			<div class="alx_reviews_form_textarea_bg">
                <?=\ALTASIB\Review\Tools::showLHE("MESSAGE_PLUS",$arResult["arMessage"]["MESSAGE_PLUS"],"MESSAGE_PLUS");?>
			</div>
            <?/*if($arParams["SHOW_CNT"]):?>
            <div class="alx_reviews_form_item_pole_textarea_dop_txt">
            <?=GetMessage("ALTASIB_TP_SCORE")?><span id="review_cnt_p" class="alx_reviews_red_txt">0</span>
            </div>
            <?endif;*/?>            
		</div>
		<div class="alx_reviews_form_item_pole_textarea nedost">
			<div class="alx_reviews_form_pole_name"><span class="requred_txt">*</span> <?=GetMessage("ALTASIB_TP_MINUS")?>:</div>
			<div class="alx_reviews_form_textarea_bg">
                <?=\ALTASIB\Review\Tools::showLHE("MESSAGE_MINUS",$arResult["arMessage"]["MESSAGE_MINUS"],"MESSAGE_MINUS");?>
			</div>
            <?/*if($arParams["SHOW_CNT"]):?>
            <div class="alx_reviews_form_item_pole_textarea_dop_txt">
            <?=GetMessage("ALTASIB_TP_SCORE")?><span id="review_cnt_m" class="alx_reviews_red_txt">0</span>
            </div>
            <?endif;*/?>
		</div>
        <?endif;
		// Конец блока
		?>
		<?if($arParams['SHOW_VOTE_BLOCK']): 
		// Рейтинг товара
		?>
        <div class="alx_reviews_form_vote" id="alx_reviews_form_vote">
          
            <?if($arParams['SHOW_MAIN_RATING']):?>
                <div class="alx_reviews_form_vote_group_name"><?=GetMessage("ALTASIB_TP_VOTE")?>:</div>
       		     	<div class="alx_reviews_form_vote_items" onmouseout="jsReviewVote.Restore();">
                        <?
                            for($i=1; $i<=5; $i++):
                                $class = "alx_reviews_form_vote_item";
                                
                                if($arResult["arMessage"]["RATING"]>0 && $i<=$arResult["arMessage"]["RATING"]):
                                    $class = "alx_reviews_form_vote_item alx_reviews_form_vote_item_sel";
                        ?>
                                    <script>
                                    jsReviewVote.Set(<?=$i?>,'RATING',0);
                                    </script>                        
                                <?endif;?>
        				    <div id="altasib_item_vote_0_<?=$i?>" onmouseover="jsReviewVote.Curr(<?=$i?>,0)" onmouseout="jsReviewVote.Out(0)" onclick="jsReviewVote.Set(<?=$i?>,'RATING',0)" class="<?=$class?>"></div>
                        <?endfor;?>
                    </div>
    			
            <?endif;?>
            <?if(count($arParams["USER_FIELDS_RATING"]) > 0):?>
                <?foreach($arParams["USER_FIELDS_RATING"] as $FIELD_NAME=>$arUserField):?>
                <div class="alx_reviews_form_pole_name"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="requred_txt">*</span><?endif;?> <?=$arUserField["EDIT_FORM_LABEL"]?>:</div>
                <?$APPLICATION->IncludeComponent(
                       "bitrix:system.field.edit",
                       $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                       array("bVarsFromForm" => $arResult, "arUserField" => $arUserField), null, array("HIDE_ICONS"=>"Y"));?>            
					   <div class="alx_clear_block">&nbsp;</div>
                <?endforeach;?>
				
            <?endif;?> 
			<div class="alx_clear_block">&nbsp;</div>			
		</div>
        <?
		// Конец Рейтинг товара
		endif;?>
		<div class="alx_reviews_form_item_pole_textarea comment">
			
			<div class="alx_reviews_form_textarea_bg">
                <?=\ALTASIB\Review\Tools::showLHE("MESSAGE",$arResult["arMessage"]["MESSAGE"],"MESSAGE",200,"oLHErwc");?>
			</div>

			<div class="alx_clear_block"></div>
			
		</div>
		
        
        <?if(count($arParams["USER_FIELDS"]) > 0):?>
		<div class="alx_reviews_form_poles_group">
			<div class="alx_reviews_form_poles_group_border_top">&nbsp;</div>
            <?foreach($arParams["USER_FIELDS"] as $FIELD_NAME=>$arUserField):?>
            <div class="alx_reviews_form_item_pole_uf">
                <div class="alx_reviews_form_pole_name"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="requred_txt">*</span><?endif;?> <?=$arUserField["EDIT_FORM_LABEL"]?>:</div>
               <?
                    if($arUserField["USER_TYPE"]["USER_TYPE_ID"]=="string_formatted")
                        $classUF = "alx_reviews_form_textarea_bg";
                    elseif($arUserField["USER_TYPE"]["USER_TYPE_ID"]=="ALTASIB_REVIEW_RATING")
                        $classUF = "alx_reviews_form_field_vote";
                    else
                        $classUF = "alx_reviews_form_field";
                ?>
                <div class="<?=$classUF?>">
					<?$APPLICATION->IncludeComponent(
                       "bitrix:system.field.edit",
                       $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                       array("bVarsFromForm" => $arResult, "arUserField" => $arUserField), null, array("HIDE_ICONS"=>"Y"));
                    ?>
                </div>
				 <div class="alx_clear_block"></div>
            </div>
           
            <?endforeach;?>
			<div class="alx_reviews_form_poles_group_border_bottom">&nbsp;</div>
		</div>
        <?endif;?>

		

	</div>
	
	<div class="alx_reviews_form_submit_block">
			<div class="">
			<input id="newReview" type="submit" name="review_add_btn" value="<?=GetMessage("ALTASIB_TP_SEND")?>" />
			</div>		
	</div>    
	</div>
</form>
</div>