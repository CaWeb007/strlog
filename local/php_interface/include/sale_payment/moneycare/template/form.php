<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/**@var $params array*/
?>
<div class="money-care-container">
    <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled </p>
    <p>
        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled
        <ol>
            <li>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled </li>
            <li>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled </li>
            <li>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled </li>
        </ol>
    </p>
    <a class="form-button" href="<?=$params['CREATE_URL']?>" target="_self"><?=Loc::getMessage('MC_PAY')?></a>
</div>
