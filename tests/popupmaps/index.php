<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
$api = 'ad511be7-b59e-446b-87cf-e10964a25c8a';
\Bitrix\Main\Page\Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?apikey='.$api.'&lang=ru_RU');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/tests/popupmaps/script.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/tests/popupmaps/style.css');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/tests/popupmaps/ui/jquery-ui.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/tests/popupmaps/ui/jquery-ui.css');
$options = array(
    'currentDeliveryMethod' => 'PICKUP',
    'currentStoreId' => 88
);
$db = \Bitrix\Catalog\StoreTable::getList(array('filter' => array('ACTIVE' => 'Y')));
while ($ar = $db->fetch()){
    $options['storeData'][(int)$ar['ID']] = $ar;
}
?>

<div class="popup-delivery-wrapper" id="caweb_delivery_maps">
    <div class="popup-delivery-title">Выберите способ доставки</div>
    <div class="popup-delivery-tabs-wrapper">
        <div class="popup-delivery-tabs" id="caweb_delivery_tabs">
            <ul>
                <li data-deliveryMethod="PICKUP"><a href="#caweb_delivery_tab_pickup">Самовывоз</a></li>
                <li data-deliveryMethod="DELIVERY"><a href="#caweb_delivery_tab_delivery">Доставка</a></li>
            </ul>
            <div class="popup-delivery-tab-pickup-wrapper" id="caweb_delivery_tab_pickup">
                <div class="popup-delivery-tab-pickup">
                    <div class="popup-delivery-tab-pickup-list-wrapper">
                        <div class="popup-delivery-tab-pickup-item-wrapper" id="caweb_delivery_pickup_list">
                            <?foreach ($options['storeData'] as $id => $array):?>
                                <div class="popup-delivery-tab-pickup-item" id="caweb_delivery_pickup_list_item" data-id="<?=$id?>">
                                    <div class="popup-delivery-tab-pickup-item-address">
                                        <?=$array['ADDRESS']?>
                                    </div>
                                    <div class="popup-delivery-tab-pickup-item-schedule">
                                        <?=$array['SCHEDULE']?>
                                    </div>
                                    <div class="popup-delivery-tab-pickup-item-phone">
                                        <?=$array['PHONE']?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                    <div class="popup-delivery-tab-pickup-map-wrapper">
                        <div class="popup-delivery-tab-pickup-map" id="caweb_delivery_tab_pickup_map"></div>
                    </div>
                </div>
            </div>
            <div class="popup-delivery-tab-delivery-wrapper" id="caweb_delivery_tab_delivery">
                <div class="popup-delivery-tab-delivery-map-wrapper">
                    <div class="popup-delivery-tab-delivery-map" id="caweb_delivery_tab_delivery_map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        CawebDeliveryMap.init(
            <?=Bitrix\Main\Web\Json::encode($options)?>
        )
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>