<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Оплата заказа");
?>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat&amp;apikey=ad511be7-b59e-446b-87cf-e10964a25c8a" type="text/javascript"></script>
    <style type="text/css">
        #map {
            width: 100%;
            height: 600px;
            padding: 0;
            margin: 0;
        }
    </style>
    <script>
        ymaps.ready(init);
        var data = {
            "type":"FeatureCollection",
            "metadata":{
                "name":"Без названия",
                "creator":"Yandex Map Constructor"
            },
            "features":[
                {
                    "type":"Feature",
                    "id":0,
                    "geometry":{
                        "type":"Polygon",
                        "coordinates":[
                            [
                                [104.1239331628415,52.36232835134156],
                                [104.134125557098,52.35026748018316],
                                [104.18837055221523,52.32313814587976],
                                [104.20871242538415,52.33160471402492],
                                [104.2008160020443,52.33717808728186],
                                [104.18090328231776,52.349952118916185],
                                [104.15845855728111,52.356416572946046],
                                [104.1387174989315,52.361934257510676],
                                [104.1239331628415,52.36232835134156]]
                        ]
                    },
                    "properties":{
                        "description":"400 рублей",
                        "fill":"#ed4543",
                        "fill-opacity":0.6,
                        "stroke":"#ed4543",
                        "stroke-width":"5",
                        "stroke-opacity":0.9
                    }
                },
                {
                    "type":"Feature",
                    "id":1,
                    "geometry":{
                        "type":"Polygon",
                        "coordinates":[
                            [
                                [104.22506317153889,52.3251892031507],
                                [104.23085674301106,52.311803016756784],
                                [104.23686489120445,52.3115925923455],
                                [104.23480495468098,52.3252680880605],
                                [104.22506317153889,52.3251892031507]
                            ]
                        ]
                    },
                    "properties":{
                        "description":"600 рублей",
                        "fill":"#1e98ff",
                        "fill-opacity":0.6,
                        "stroke":"#1e98ff",
                        "stroke-width":"5",
                        "stroke-opacity":0
                    }
                }
            ]
        };
        function init() {
            var myMap = new ymaps.Map('map', {
                    center: [104.23711775741876,52.354539436513356],
                    zoom: 10,
                    controls: ['geolocationControl', 'searchControl']
                }),
                deliveryPoint = new ymaps.GeoObject({
                    geometry: {type: 'Point'},
                    properties: {iconCaption: 'Адрес'}
                }, {
                    preset: 'islands#blackDotIconWithCaption',
                    draggable: true,
                    iconCaptionMaxWidth: '215'
                }),
                searchControl = myMap.controls.get('searchControl');
            searchControl.options.set({noPlacemark: true, placeholderContent: 'Введите адрес доставки'});
            myMap.geoObjects.add(deliveryPoint);

            function onZonesLoad(json) {
                // Добавляем зоны на карту.
                var deliveryZones = ymaps.geoQuery(json).addToMap(myMap);
                // Задаём цвет и контент балунов полигонов.
                deliveryZones.each(function (obj) {
                    obj.options.set({
                        fillColor: obj.properties.get('fill'),
                        fillOpacity: obj.properties.get('fill-opacity'),
                        strokeColor: obj.properties.get('stroke'),
                        strokeWidth: obj.properties.get('stroke-width'),
                        strokeOpacity: obj.properties.get('stroke-opacity')
                    });
                    obj.properties.set('balloonContent', obj.properties.get('description'));
                });

                // Проверим попадание результата поиска в одну из зон доставки.
                searchControl.events.add('resultshow', function (e) {
                    highlightResult(searchControl.getResultsArray()[e.get('index')]);
                });

                // Проверим попадание метки геолокации в одну из зон доставки.
                myMap.controls.get('geolocationControl').events.add('locationchange', function (e) {
                    highlightResult(e.get('geoObjects').get(0));
                });

                // При перемещении метки сбрасываем подпись, содержимое балуна и перекрашиваем метку.
                deliveryPoint.events.add('dragstart', function () {
                    deliveryPoint.properties.set({iconCaption: '', balloonContent: ''});
                    deliveryPoint.options.set('iconColor', 'black');
                });

                // По окончании перемещения метки вызываем функцию выделения зоны доставки.
                deliveryPoint.events.add('dragend', function () {
                    highlightResult(deliveryPoint);
                });

                function highlightResult(obj) {
                    // Сохраняем координаты переданного объекта.
                    var coords = obj.geometry.getCoordinates(),
                        // Находим полигон, в который входят переданные координаты.
                        polygon = deliveryZones.searchContaining(coords).get(0);

                    if (polygon) {
                        // Уменьшаем прозрачность всех полигонов, кроме того, в который входят переданные координаты.
                        deliveryZones.setOptions('fillOpacity', 0.4);
                        polygon.options.set('fillOpacity', 0.8);
                        // Перемещаем метку с подписью в переданные координаты и перекрашиваем её в цвет полигона.
                        deliveryPoint.geometry.setCoordinates(coords);
                        deliveryPoint.options.set('iconColor', polygon.properties.get('fill'));
                        // Задаем подпись для метки.
                        if (typeof(obj.getThoroughfare) === 'function') {
                            setData(obj);
                        } else {
                            // Если вы не хотите, чтобы при каждом перемещении метки отправлялся запрос к геокодеру,
                            // закомментируйте код ниже.
                            ymaps.geocode(coords, {results: 1}).then(function (res) {
                                var obj = res.geoObjects.get(0);
                                setData(obj);
                            });
                        }
                    } else {
                        // Если переданные координаты не попадают в полигон, то задаём стандартную прозрачность полигонов.
                        deliveryZones.setOptions('fillOpacity', 0.4);
                        // Перемещаем метку по переданным координатам.
                        deliveryPoint.geometry.setCoordinates(coords);
                        // Задаём контент балуна и метки.
                        deliveryPoint.properties.set({
                            iconCaption: 'Доставка транспортной компанией',
                            balloonContent: 'Cвяжитесь с оператором',
                            balloonContentHeader: ''
                        });
                        // Перекрашиваем метку в чёрный цвет.
                        deliveryPoint.options.set('iconColor', 'black');
                    }

                    function setData(obj){
                        var address = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                        if (address.trim() === '') {
                            address = obj.getAddressLine();
                        }
                        var price = polygon.properties.get('description');
                        //price = price.match(/<strong>(.+)<\/strong>/)[1];
                        deliveryPoint.properties.set({
                            iconCaption: price,
                            balloonContent: price,
                            balloonContentHeader: price
                        });
                    }
                }
            }
            onZonesLoad(data);
        }
    </script>
    <div id="map"></div>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>