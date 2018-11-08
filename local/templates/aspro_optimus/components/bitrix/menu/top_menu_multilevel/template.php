<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){?>
	<!--<ul class="menu top menu_top_block catalogfirst visible_on_ready">-->
	<ul class="top-dropdown-menu">
		<?foreach( $arResult as $key => $arItem ){
			if(isset($arItem["PARAMS"]["NOT_VISIBLE"]) && $arItem["PARAMS"]["NOT_VISIBLE"]=="Y")
				continue;?>
			<li class="<?=($arItem["SELECTED"] ? "current" : "");?> <?=($arItem['PARAMS']['CLASS'] ? $arItem['PARAMS']['CLASS'] : "");?> <?=($arItem["CHILD"] ? "top-dropdown-menu-has-child" : "");?>">
				<a class="<?=($arItem["CHILD"] ? "icons_fa parent" : "");?>" href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
				<?if($arItem["CHILD"]){?>
					<ul class="dropdown">
						<?foreach($arItem["CHILD"] as $arChildItem){?>
							<li class="<?=($arChildItem["CHILD"] ? "has-child" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">

								<?if(!empty($arChildItem["PARAMS"]["ICON"])):?>
								<div class="menu-projects">
									<img src="<?=$arChildItem["PARAMS"]["ICON"];?>" />
									<span><?=$arChildItem["PARAMS"]["DESC"];?></span>
								</div>
								<?else:?>
								<a class="<?=($arChildItem["CHILD"] ? "icons_fa parent" : "");?>" href="<?=$arChildItem["LINK"];?>"><?=$arChildItem["TEXT"];?></a>
								<?endif;?>

								<?if($arChildItem["CHILD"]){?>
									<ul class="dropdown">
										<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
											<li class="menu_item1 <?if($arChildItem1["SELECTED"]){?> current <?}?>">
												<a href="<?=$arChildItem1["LINK"];?>"><span class="text"><?=$arChildItem1["TEXT"];?></span></a>
											</li>
										<?}?>
									</ul>
								<?}?>
							</li>
						<?}?>
					</ul>
				<?}?>
			</li>
		<?}?>
		<li class="more">
			<a href="javascript:;" rel="nofollow"></a>
			<ul class="dropdown"></ul>
		</li>
	</ul>
<div class="mobile_menu_wrapper" style="display: none;">
		<ul class="mobile_menu">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="icons_fa <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current" : "");?>">
					<a class="dark_link <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
					<?if($arItem["CHILD"]){?>
						<ul class="dropdown">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<li class="full <?if($arChildItem["SELECTED"]){?> current <?}?>">
									<a class="icons_fa <?=($arChildItem["CHILD"] ? "parent" : "");?>" href="<?=($arChildItem["SECTION_PAGE_URL"] ? $arChildItem["SECTION_PAGE_URL"] : $arChildItem["LINK"] );?>"><?=($arChildItem["NAME"] ? $arChildItem["NAME"] : $arChildItem["TEXT"] );?></a>
								</li>
							<?}?>
						</ul>
					<?}?>
				</li>
			<?}?>
			<li class="search">
				<div class="search-input-div">
					<input class="search-input" type="text" autocomplete="off" maxlength="50" size="40" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>" value="" name="q">
				</div>
				<div class="search-button-div">
					<button class="button btn-search btn-default" value="<?=GetMessage("CT_BST_SEARCH2_BUTTON")?>" name="s" type="submit"><?=GetMessage("CT_BST_SEARCH2_BUTTON")?></button>
				</div>
			</li>
		</ul>
	</div>
<?}?>

<script>
	$(document).ready(function(){
		var projectsMaxWidth = $('#wrapper-anchor').css('width');
		var menuprojects = $('.menu-projects');
		var menuprojectsParent = $(menuprojects).parent().parent();
		$(menuprojectsParent).parent().css('position', 'static');
		$(menuprojectsParent).addClass('menu-projects-parent');
		//$(menuprojectsParent).css('max-width', parseInt(projectsMaxWidth));
		$(menuprojectsParent).parent().mouseover(function(){
			$(this).children('.dropdown').css('dispaly', 'flex');
		});
	});
</script>
<style>
	.top-dropdown-menu-has-child:hover > .menu-projects-parent{
		display: flex;
		width: 100%;
		max-width: 1160px;
		left: 24px;
		right: 24px;
	}
	.menu-projects-parent{
		justify-content: space-around;
		align-items: flex-start;
		flex-wrap: nowrap;
		width: 100%;
		left: 24px;
		right: 24px;
	}
	.menu-projects-parent li{
		width: 33.3333%;
	}
	.menu-projects{
		box-sizing: border-box;
		/*width: 100%;*/
		height: auto;
		margin: 0 auto;
		padding: 10px;
	}
	.menu-projects img{
		display: block;
		width: auto;
		max-width: 100%;
		height: auto;
		max-height: 100%;
		margin: 0 auto 10px;
		padding: 0;
	}
	@media screen and (min-width: 300px) and (max-width: 1160px){
		.menu-projects-parent{
			left: 0 !important;
		}
	}
</style>