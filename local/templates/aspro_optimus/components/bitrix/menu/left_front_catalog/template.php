<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?/*if( !empty( $arResult ) ){
	global $TEMPLATE_OPTIONS;?>
	<div class="menu_top_block catalog_block">
		<ul class="menu dropdown">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]);?> v_<?=strtolower($TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"]);?>">
					<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" ><?=$arItem["NAME"]?><div class="toggle_block"></div></a>
					<?if($arItem["CHILD"]){?>
						<ul class="strlog-dropdown-wrap dropdown">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
									<?//if($arChildItem["IMAGES"] && $TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"] != 'BOTTOM'){?>
										<!--<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>-->
									<?//}?>
                                    <a class="section dark_link" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span><?=$arChildItem["NAME"];?>&nbsp;<span class="section_count_1"><?=$arChildItem["COUNT"];?></span></span></a>
									<?if($arChildItem["CHILD"]){?>
										<ul class="dropdown">
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
												<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem1["COUNT"];?></span></span></a>
												</li>
											<?}?>
										</ul>
									<?}?>
									<div class="clearfix"></div>
								</li>
							<?}?>
						</ul>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
<?}*/?>

<?if( !empty( $arResult ) ){
	global $TEMPLATE_OPTIONS;?>
	<div class="menu_top_block catalog_block">
		<ul class="menu dropdown">
			<?foreach( $arResult as $key => $arItem ){?>
				<?if(!$arItem["NAME"]) continue;?>
						<?if((int)$arItem['ID'] == 2155) continue;?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]);?> v_<?=strtolower($TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"]);?>">
					<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" ><?=$arItem["NAME"]?><div class="toggle_block"></div></a>
					<?if($arItem["CHILD"]){?>
						<!--ul class="strlog-dropdown-wrap dropdown"-->
					<div class="strlog-dropdown-wrap dropdown" style="max-height:<?=$arResult['MAX_COLUM_HEIGHT']?>px;">
                            <div class="column-links">
                            <div class="column-links-wrapp">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<!--li class="first-li<?=($arChildItem["CHILD"] ? " has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>"-->
									<?//if($arChildItem["IMAGES"] && $TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"] != 'BOTTOM'){?>
										<!--<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>-->
									<?//}?>
									<?if(isset($arChildItem['endColumn']) && $arChildItem['endColumn'] == true):?></div></div><div class="column-links"><div class="column-links-wrapp"><?endif?>
                                    <a class="link-column section dark_link" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span><?=$arChildItem["NAME"];?>&nbsp;<span class="section_count_1"><?=$arChildItem["ELEMENT_CNT"];?></span></span></a>
									<?if($arChildItem["CHILD"]){?>
									<?$cnChild = count($arChildItem["CHILD"]);?>
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
												<?$cnChild--;?>
												<!--li class="second-li menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>"-->
													<a class="link-column parent1 section1<?if($cnChild==0):?> end-child<?endif?>" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem1["ELEMENT_CNT"];?></span></span></a>
												<!--/li-->
											<?}?>
									<?}?>
									<!--div class="clearfix"></div-->
								<!--/li-->
							<?}?>
							</div>
							</div>
						<!--/ul-->
						</div>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
<?}?>

<script>
    var menuHeight = $('.catalog_block').css('height');
    $('.strlog-dropdown-wrap').css('height', menuHeight);
    $('.strlog-dropdown-wrap').children('.first-li').each(function () {
        console.log($(this).children('a').text());
        console.log($(this).children('.second-li a span').children('a').children('span').text());
    });
</script>

<?php/*
<?if( !empty( $arResult ) ){
	global $TEMPLATE_OPTIONS;?>
	<div class="menu_top_block catalog_block">
		<ul class="menu dropdown">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]);?> v_<?=strtolower($TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"]);?>">
					<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" ><?=$arItem["NAME"]?><div class="toggle_block"></div></a>
					<?if($arItem["CHILD"]){?>
                        <ul class="dropdown strlog-dropdown-left-menu-first">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
									<?//if($arChildItem["IMAGES"] && $TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"] != 'BOTTOM'){?>
										<!--<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>-->
									<?//}?>
                                    <a class="section dark_link" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span><?=$arChildItem["NAME"];?>&nbsp;<span class="section_count_1"><?=$arChildItem["COUNT"];?></span></span></a>
									<?if($arChildItem["CHILD"]){?>
                                        <?php $setWidth = '310px';?>
                                        <?php if (count($arChildItem["CHILD"]) < 5) : ?>
                                        <?php $setWidth = '310px'; ?>
                                        <?php elseif(count($arChildItem["CHILD"]) < 8) : ?>
                                        <?php $setWidth = '620px'; ?>
                                        <?php elseif(count($arChildItem["CHILD"]) >= 8) : ?>
                                        <?php $setWidth = '930px'; ?>
                                        <?php else: ?>
                                        <?php $setWidth = '310px'; ?>
                                        <?php endif; ?>
                                        <ul class="dropdown strlog-dropdown-left-menu-second" style="width: <?=$setWidth;?>; min-width: <?=$setWidth;?>;">
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
                                                <?php if (count($arChildItem["CHILD"]) < 5): ?>
												<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem1["COUNT"];?></span></span></a>
												</li>
                                                <?php elseif (count($arChildItem["CHILD"]) < 8): ?>
                                                <li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?> strlog-li-width-50">
                                                    <a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem1["COUNT"];?></span></span></a>
                                                </li>
                                                <?php elseif ((count($arChildItem["CHILD"]) >= 8)): ?>
                                                <li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?> strlog-li-width-33">
                                                    <a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?>&nbsp;<span class="section_count_2"><?=$arChildItem1["COUNT"];?></span></span></a>
                                                </li>
                                                <?php endif; ?>
											<?}?>
										</ul>
									<?}?>
									<div class="clearfix"></div>
								</li>
							<?}?>
						</ul>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
<?}?>
<script>
    $(document).ready(function () {
        var menuWidth = $('.catalog_block').css('height');
        $('.catalog_block .dropdown li').children('.strlog-dropdown-left-menu-first').each(function () {
            var secondMenuWidth = $(this).css('height');
            if (parseInt(secondMenuWidth) < parseInt(menuWidth)) {
                $(this).css('height', parseInt(menuWidth) + 'px');
            }
        });
    });
</script>
*/?>