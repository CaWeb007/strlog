<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/**@var $params array*/
?>
<div class="money-care-container">
    <?switch($params['STATUS']):case 'PROCESSING': case 'CONTRUCT':?>
        <p>
            Ожидается обработка сервисом MoneyCare.
        </p>
        <div>Заявка в статусе: <b><?=Loc::getMessage('MC_STATUS_'.$params['STATUS'])?></b></div>
    <?break; case 'END':?>
        <p>Заказ оплачен</p>
    <?break; default:?>
        <p>Статус полученный от платежной системы: <?=Loc::getMessage('MC_STATUS_'.$params['STATUS'])?></p>
    <?break;endswitch;?>
</div>