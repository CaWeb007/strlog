<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
\Bitrix\Main\Page\Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1?lang=ru_RU');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/tests/popupmaps/script.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/tests/popupmaps/style.css');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/tests/popupmaps/ui/jquery-ui.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/tests/popupmaps/ui/jquery-ui.css');
$options = array(
    'currentDeliveryMethod' => 'DELIVERY'
)
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
                            <div class="popup-delivery-tab-pickup-item" id="caweb_delivery_pickup_list_item" data-id="1">
                                <div class="popup-delivery-tab-pickup-item-address">
                                    г.Иркутск, ул. Трактовая 18 Б/А
                                </div>
                                <div class="popup-delivery-tab-pickup-item-schedule">
                                    Пн-Вс, 10:00-20:00
                                </div>
                                <div class="popup-delivery-tab-pickup-item-phone">
                                    280-900
                                </div>
                            </div>
                            <div class="popup-delivery-tab-pickup-item" id="caweb_delivery_pickup_list_item" data-id="2">
                                <div class="popup-delivery-tab-pickup-item-address">
                                    г.Иркутск, ул. Трактовая 18 Б/А
                                </div>
                                <div class="popup-delivery-tab-pickup-item-schedule">
                                    Пн-Вс, 10:00-20:00
                                </div>
                                <div class="popup-delivery-tab-pickup-item-phone">
                                    280-900
                                </div>
                            </div>
                            <div class="popup-delivery-tab-pickup-item" id="caweb_delivery_pickup_list_item" data-id="3">
                                <div class="popup-delivery-tab-pickup-item-address">
                                    г.Иркутск, ул. Трактовая 18 Б/А
                                </div>
                                <div class="popup-delivery-tab-pickup-item-schedule">
                                    Пн-Вс, 10:00-20:00
                                </div>
                                <div class="popup-delivery-tab-pickup-item-phone">
                                    280-900
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-delivery-tab-pickup-map-wrapper">
                        <div class="popup-delivery-tab-pickup-map" id="caweb_delivery_tab_pickup_map"></div>
                    </div>
                </div>
            </div>
            <div class="popup-delivery-tab-delivery-wrapper" id="caweb_delivery_tab_delivery">
                <div class="popup-delivery-tab-delivery-map-wrapper">
                    <div class="popup-delivery-tab-delivery-map" id="caweb_delivery_tab_pickup_map"></div>
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