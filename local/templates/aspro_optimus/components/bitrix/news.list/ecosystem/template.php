<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalJS($templateFolder.'/js/position.min.js');
?>
<section class="strlog-ecosystem" id="ecosystem-wrapper">
    <div class="ecosystem-tiles">
        <div class="ecosystem_body">
            <?$i = 0?>
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
                <?$logo = $arItem['ID'] == 43647?>
                <?if($i === 0):?>
                    <div class="ecosystem_row">
                        <div class="ecosystem_column ecosystem_column-one-third">
                <?endif;?>
                <?if($i === 1):?>
                        </div>
                        <div class="ecosystem_column ecosystem_column-two-thirds">
                <?endif;?>
                <?
                $str = '';
                if(($i === 0) && !empty($arItem['PREVIEW_PICTURE']))
                    $str ='style=\'background-image:url("'.$arItem["PREVIEW_PICTURE"]['SRC'].'");\' '
                ?>
                <div <?if(!empty($str)) echo $str;?>class="widget widget-hover<?
                    if($i === 0) echo ' widget-full widget-main widget-design';
                    if($i === 3) echo ' widget-full widget-horizontal';
                    if($i >= 4) echo ' widget-small-icon';
                    if(($i === 1) || ($i === 2)) echo ' widget-half';
                    if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU'])) echo ' widget-with_submenu';
                    ?>" data-id="<?echo $arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU'])):?>
                        <div class="widget_toggle-icon-wrapper">...</div>
                    <?endif?>
                    <?if($logo):?>
                        <div class="widget_perimetr-logo"></div>
                    <?endif?>
                    <div class="widget-inner">
                        <?if (($i !== 0) && !empty($arItem['DISPLAY_PROPERTIES']['ICON'])):?>
                            <div class="widget_icon">
                                <i>
                                    <object data="<?=$arItem['DISPLAY_PROPERTIES']['ICON']['FILE_VALUE']['SRC']?>"
                                            type="image/svg+xml"></object>
                                </i>
                            </div>
                        <?endif?>
                        <p class="widget-title"><?=$arItem['NAME']?></p>
                        <p class="widget-description"><?=$arItem['PREVIEW_TEXT']?></p>
                    </div>
                    <?if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU'])):?>
                        <ul id="widget-submenu">
                            <?foreach ($arItem['DISPLAY_PROPERTIES']['SUB_MENU']['VALUE'] as $key => $subLink):?>
                                <li><a href="<?=$subLink?>"><?=$arItem['DISPLAY_PROPERTIES']['SUB_MENU']['DESCRIPTION'][$key]?></a></li>
                            <?endforeach;?>
                        </ul>
                    <?elseif (!empty($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])):?>
                        <a id="widget-href" href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"></a>
                    <?endif;?>
                </div>
                <?if($i === 3):?>
                        </div>
                    </div>
                    <div class="ecosystem_row">
                        <div class="ecosystem_column">
                <?endif;?>
                <?$i++?>
            <?endforeach;?>
                </div>
            </div>
        </div>
    </div>
    <div class="ecosystem-slider">
        <ul class="ecosystem_slider_body slides">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $str = '';
                if(!empty($arItem['PREVIEW_PICTURE']))
                    $str ='style=\'background-image:url("'.$arItem["PREVIEW_PICTURE"]['SRC'].'");\' '
                ?>
                <li <?if(!empty($str)) echo $str;?>class="widget<?
                    if(!empty($arItem['PREVIEW_PICTURE'])) echo ' widget-design';
                    if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU']['VALUE'])) echo ' widget-with_submenu';
                    ?>" data-id="<?echo $arItem['ID']?>">
                    <?if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU']['VALUE'])):?>
                        <div class="widget_toggle-icon-wrapper">...</div>
                    <?endif?>
                    <div class="widget-inner">
                        <?if (!empty($arItem['DISPLAY_PROPERTIES']['ICON']['VALUE'])):?>
                            <div class="widget_icon">
                                <i>
                                    <object data="<?=$arItem['DISPLAY_PROPERTIES']['ICON']['FILE_VALUE']['SRC']?>"
                                            type="image/svg+xml"></object>
                                </i>
                            </div>
                        <?endif?>
                        <p class="widget-title"><?=$arItem['NAME']?></p>
                        <p class="widget-description"><?=$arItem['PREVIEW_TEXT']?></p>
                    </div>
                    <?if (!empty($arItem['DISPLAY_PROPERTIES']['SUB_MENU']['VALUE'])):?>
                        <ul id="widget-submenu">
                            <?foreach ($arItem['DISPLAY_PROPERTIES']['SUB_MENU']['VALUE'] as $key => $subLink):?>
                                <li><a href="<?=$subLink?>"><?=$arItem['DISPLAY_PROPERTIES']['SUB_MENU']['DESCRIPTION'][$key]?></a></li>
                            <?endforeach;?>
                        </ul>
                    <?elseif (!empty($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])):?>
                        <a id="widget-href" href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"></a>
                    <?endif;?>
            <?endforeach;?>
        </ul>
    </div>
    <div class="ecosystem-popup" style="opacity: 0; display: none">
        <div class="ecosystem-popup__header">
            <div class="ecosystem-popup__title"></div>
            <button class="ecosystem-popup__close"><i class="fa fa-close"></i></button>
        </div>
        <div class="ecosystem-popup__arrow"></div>
        <div class="ecosystem-popup__body"></div>
    </div>
</section>
<script>
    JSEcosystem();
</script>