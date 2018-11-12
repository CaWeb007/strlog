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
<?/*p($arResult);*/?>
<div class="alx_reviews_list_success">Ваш отзыв добавлен!</div>
<div class="alx_reviews_list" id="alx_reviews_list">

	<?foreach($arResult["ITEMS"] as $arReview):
    if(!aReview::AllowVote($arReview["ID"]) || ($USER->GetID()>0 && $USER->GetID()==$arReview["USER_ID"]))
        $arReview['ALLOW_VOTE'] = false;
    else
        $arReview['ALLOW_VOTE'] = true;
    ?>
	<?if($arReview["APPROVED"]=="N" && (($USER->GetID()==$arReview["USER_ID"] && $USER->GetID()>0) 
		|| ($APPLICATION->get_cookie("REVIEW_AUTHOR_EMAIL", "ALTASIB_REVIEW") == $arReview["AUTHOR_EMAIL"]
		&& $APPLICATION->get_cookie("REVIEW_AUTHOR_NAME", "ALTASIB_REVIEW") == $arReview["AUTHOR_NAME"])
		))
			ShowNote(GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_MODER"));
		elseif($arReview["APPROVED"]=="N" && !$arParams["ALLOW_EDIT"])
			continue;?>
	<a name="review<?=$arReview["ID"];?>"></a>
	<div class="alx_reviews_item<?if($arReview["APPROVED"]!="Y"):?> hide<?endif;?><?if($arReview["IS_BEST"]=="Y"):?> best<?endif;?>" id="review-list-review-<?=$arReview["ID"]?>">
		<div class="alx_reviews_item_line"></div>
        <?
        if($arReview["VOTE"]<0)
            $class = "no";
        else
            $class = "yes";
        ?>
        
		<div class="alx_reviews_item_author_info">
			
			<div class="alx_reviews_user_name">
				<?if(strlen($arReview["SHOW_USER_PATH"])>0):?><a href="<?=$arReview["SHOW_USER_PATH"]?>" target="_blank" rel="nofollow"><?endif;?><?=$arReview["AUTHOR_NAME"];?><?if(strlen($arReview["SHOW_USER_PATH"])>0):?></a><?endif;?>
			</div>
			
			<div class="alx_reviews_time"><?=$arReview["POST_DATE_FORMAT"];?></div>
			
		
			
		</div>
		<div class="alx_reviews_vote_item">
            <?if($arParams["SHOW_MAIN_RATING"]):?>
			<div class="alx_reviews_form_vote_items">
				<?for($ii=1;$ii<=5;$ii++):
					$class="alx_reviews_form_vote_item";
					if($ii<=$arReview["RATING"])
						$class="alx_reviews_form_vote_item alx_reviews_form_vote_item_sel";
					?>
					<div class="<?=$class?>"></div>
				<?endfor;?> 
			</div>
            <?endif;?>
            <?if(!empty($arReview["USER_FIELDS_RATING"])):?>
            <?if($arParams["SHOW_MAIN_RATING"]):?>
			 <div class="alx_reviews_item_vote_show"><a href="javascript:void(0)" onclick="jsReview.ShowVotes(<?=$arReview["ID"]?>)"><?=GetMessage('ALTASIB_REVIEW_T_REVIEW_LIST_SHOW_ALL_VOTES')?></a></div>
            <?endif;?>
			<div class="alx_reviews_item_vote_list<?if(!$arParams["SHOW_MAIN_RATING"]):?> show<?endif;?>" id="review_all_votest_<?=$arReview["ID"]?>">
				<?foreach($arReview["USER_FIELDS_RATING"] as $k=>$arR):?>
					<div class="alx_reviews_item_vote">
						<div class="alx_review_rating_title"><?=$arR["EDIT_FORM_LABEL"]?></div>
						<div class="alx_reviews_form_vote_items">
						<?for($ii=1;$ii<=5;$ii++):
							$class="alx_reviews_form_vote_item";
							if($ii<=(int)$arR["VALUE"])
								$class="alx_reviews_form_vote_item alx_reviews_form_vote_item_sel";
							?>
							<div class="<?=$class?>"></div>
						<?endfor;?> 
						</div>                            
					</div>
				<?endforeach;?>  
				<div class="alx_clear_block">&nbsp;</div>
			</div>
            <?endif;?>
			<div class="alx_clear_block">&nbsp;</div>
		</div>
		<div class="alx_reviews_item_title"><?=$arReview["TITLE"];?></div>
		<div class="alx_reviews_item_sec_list" id="review_item_e_<?=$arReview["ID"]?>">
          
			<div class="alx_reviews_item_sec">
				
				<div class="alx_review_mess"><?=$arReview["MESSAGE_HTML"];?></div>
			</div>
			
            <?if(count($arReview["USER_FIELDS"]) > 0):?>
            <?foreach($arReview["USER_FIELDS"] as $FIELD_NAME=>$arUserField):
            if($arUserField["USER_TYPE_ID"] != "video" && $arUserField["USER_TYPE_ID"] != "file" && $arUserField["USER_TYPE_ID"] != "iblock_section" && $arUserField["USER_TYPE_ID"] != "iblock_element")
                continue;
                
            if ((is_array($arUserField["VALUE"]) && count($arUserField["VALUE"]) > 0) || (!is_array($arUserField["VALUE"]) && StrLen($arUserField["VALUE"]) > 0)):
            ?>
            <div class="alx_reviews_item_sec">
                    <div class="alx_reviews_title_caps"><?=$arUserField["EDIT_FORM_LABEL"]?>:</div>
                    <div class="alx_review_mess">
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:system.field.view",
                                $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                array("arUserField" => $arUserField), null, array("HIDE_ICONS"=>"Y")
                            );
                        ?>
                    </div>
    		</div>
            <?endif;?>
            <?endforeach;?>
        <?endif;?>            
		</div>
        <?if(count($arReview["FILES"])>0):?>
			<div class="alx_item_pole_rev">
                <?foreach($arReview["FILES"] as $arFile):?>
                    <?if(!stristr($arFile["CONTENT_TYPE"], "image/")):?>
                    <a href="<?=$arFile["SRC"]?>" target="_blank" rel="nofollow"><?=$arFile["ORIGINAL_NAME"]?></a>
                    <?endif;?>
                <?endforeach;?>
			</div>
            <div id="review_attach" valign="center">
			<?foreach($arReview["FILES"] as $arFile):?>
					<?if(stristr($arFile["CONTENT_TYPE"], "image/")):?>
                    <?=CFile::ShowImage($arFile["SRC"], 250, 250, "border=0", "", true);?>
					<?endif;?>
			<?endforeach;?>
            </div>
            <br />
        <?endif?>
        <?if(strlen($arReview["REPLY_HTML"])>0):?>
           <div class="altasib_reviw_answer">
    		<div class="altasib_reviw_answer_top_border">&nbsp;</div>
    		<?=$arReview["REPLY_HTML"]?>
           </div><br />
        <?endif;?>
		<?if(($arParams["ALLOW_COMPLAINT"] && (($USER->IsAuthorized() && $arReview["USER_ID"] != $USER->GetID()) || !$arParams["ONLY_AUTH_COMPLAINT"]))):?>
        <a href="javascript:void(0)" onclick="jsReview.Complaint(<?=$arReview["ID"]?>);" class="alx_reviews_violation"><?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_REPORT")?></a>
        <div style="clear: both;"></div>
        <?endif;?>        
      
     
		<div class="alx_clear_block">&nbsp;</div>
		<?if($arParams["ALLOW_EDIT"]):?>
		<div class="alx_reviews_admin_prop">
			<a href="javascript:void(0)" onclick="jsReviewAdmin.Delete(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_del"><?=GetMessage("MAIN_DELETE")?></a>
			<a href="javascript:void(0)" onclick="jsReview.Edit(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_edit"><?=GetMessage("MAIN_EDIT")?></a>
            <?if($arReview["APPROVED"]=="Y"):?>
                <a href="javascript:void(0)" id="review-hide-app-link-<?=$arReview["ID"]?>" onclick="jsReviewAdmin.Hide(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_hide"><?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_MODER_HIDE")?></a>
            <?else:?>
                <a href="javascript:void(0)" id="review-hide-app-link-<?=$arReview["ID"]?>" onclick="jsReviewAdmin.App(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_hide"><?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_MODER_APP")?></a>
            <?endif;?>
            
            <?if($arReview["IS_BEST"]=="N"):?>
                <a href="javascript:void(0)" id="review-best-app-link-<?=$arReview["ID"]?>" onclick="jsReviewAdmin.SetBest(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_hide"><?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_MODER_SET_BEST")?></a>
            <?else:?>
                <a href="javascript:void(0)" id="review-best-app-link-<?=$arReview["ID"]?>" onclick="jsReviewAdmin.DelBest(<?=$arReview["ID"]?>);" class="alx_reviews_admin_prop_hide"><?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_MODER_DEL_BEST")?></a>
            <?endif;?>
            
		</div>
		<?endif?>
	</div>
  <?endforeach;?>
  <?if($arResult["ALL_CNT"]>$arParams["REVIEWS_ON_PAGE"] && !$arParams["SHOW_ALL"]):?>
  <a href="javascript:void(0)" onclick="BX.ajax.get(CURRENT_URL,{'ALTASIB_AJAX_CALL' : 'Y','showAll' : 'Y'},function (res) {BX.showWait(BX('#alx_reviews_list'));BX('alx_reviews_list').innerHTML = res;BX.closeWait(BX('#alx_reviews_list'));})" class="alx_reviews_show_more">
		<?=GetMessage("ALTASIB_REVIEW_T_REVIEW_LIST_SHOW_ALL")?> <span class="alx_reviews_count_all"><?=$arResult["ALL_CNT"]-$arParams["REVIEWS_ON_PAGE"]?></span>
  </a>
  <?endif;?>
</div>