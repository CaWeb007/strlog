<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/**@var $params array*/
?>
<div class="money-care-container">
    <p>
        <b style="color: red">Выбранный способ оплаты влияет на конечную стоимость товара при оформлении. Спец.цена при оформлении товара в кредит не действует.</b>
    </p>
    <p>
        Для оформления кредита онлайн:
        <ol>
            <li>Проверить условия и подтвердить согласие на обработку персональных данных и и заполнить дополнительные поля в короткой анкете.</li>
            <li>После заполнения короткой заявки, заявка передается на автоматическое рассмотрение в банк за несколько минут с вами свяжется менеджер.</li>
            <li>При положительном ответе ознакамливаетесь и подписываете электронный комплект документов на кредит.</li>
        </ol>
    </p>
    <a class="form-button" href="<?=$params['CREATE_URL']?>" target="_self"><?=Loc::getMessage('MC_PAY')?></a>
</div>
