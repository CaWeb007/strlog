<? use Caweb\Main\Sale\Helper;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$accountValue = array_shift($arResult["ACCOUNT_LIST"]);?>
<?if(Helper::getInstance()->checkBonusAccess() && ($accountValue["CURRENCY"] == "RUB")):?>
<?$bonus = (float)$accountValue["ACCOUNT_LIST"]['CURRENT_BUDGET'];?>
    <div class="user-bonus-wrapper">
        <?if($bonus):?>
            <span class="user-bonus-title">Количество бонусов: <span class="user-bonus-desc"><?=$bonus?></span></span>
        <?else:?>
            <span class="user-bonus-title">У Вас нет бонусов</span>
        <?endif;?>
        <div class="user-bonus-popup-wrapper">
            <div class="user-bonus-popup">
                <span class="bonuses-quantity-title">
                Копите бонусы и оплачивайте ими ваши покупки<br>
                1 бонус = 1 рубль<br>
                <a href="http://strlogclub.ru/about/" target="_blank">Узнать больше</a>
                </span>
            </div>
        </div>
    </div>
<?endif?>