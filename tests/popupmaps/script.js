const CawebDeliveryMap = {
    init: function (options) {
        this.options = options
        this.container = $('#caweb_delivery_maps')
        this.tabs = this.container.find('#caweb_delivery_tabs')
        this.tabPickup = this.tabs.find('#caweb_delivery_tab_pickup')
        this.tabPickupList = this.tabs.find('#caweb_delivery_pickup_list').children('#caweb_delivery_pickup_list_item')
        this.tabPickupItems = this.getPickupItems()
        this.tabPickupMap = this.tabPickup.find('#caweb_delivery_tab_pickup_map')
        this.tabDelivery = this.tabs.find('#caweb_delivery_tab_delivery')
        this.tabDeliveryMap = this.tabDelivery.find('#caweb_delivery_tab_delivery_map')
        this.ymaps = {
            pickup: {},
            delivery: {}
        }
        this.options.deliveryZones =  {
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
        }

        this.tabsInit()
        this.storeItemInit()
    },
    getPickupItems: function () {
        let result = {}
        this.tabPickupList.each(function (index, item) {
            let $_item = $(item)
            result[$_item.attr('data-id')] = $_item
        })
        return result
    },
    tabsInit: function () {
        this.tabs.tabs({
            create: $.proxy(this.tabsCreateHandler, this),
            active: (this.options.currentDeliveryMethod === 'PICKUP')? 0 : 1,
            beforeActivate: $.proxy(this.tabsBeforeActivateHandler, this)
        })
    },
    tabsCreateHandler: function (event, ui){
        this.mapInit(ui.tab.attr('data-deliveryMethod'))
    },
    tabsBeforeActivateHandler: function (event, ui){
        this.mapInit(ui.newTab.attr('data-deliveryMethod'))
    },
    mapInit: function(activeTab) {
        if (activeTab === 'PICKUP' && !this.mapPickupInited)
            ymaps.ready($.proxy(this.mapPickupInit, this))
        if (activeTab === 'DELIVERY' && !this.mapDeliveryInited)
            ymaps.ready($.proxy(this.mapDeliveryInit, this))
    },
    mapPickupInit: function () {
        if (this.mapPickupInited) return
        const initStoreData = this.options.storeData[this.options.currentStoreId]
        this.ymaps.pickup.map = new ymaps.Map(this.tabPickupMap.prop('id'), {
            center: [initStoreData['GPS_S'], initStoreData['GPS_N']],
            zoom: 10
        })
        this.ymaps.pickup.placemark = {}
        for (let id in this.options.storeData){
            let store = this.options.storeData[id]
            let placemark = new ymaps.Placemark([store['GPS_S'], store['GPS_N']], {storeId: id})
            placemark.events.add('click', this.placemarkClickHandler, this)
            this.ymaps.pickup.placemark[id] = placemark
            this.ymaps.pickup.map.geoObjects.add(placemark)
        }

        this.mapPickupInited = true
    },
    placemarkClickHandler: function(element) {
        const id = element.get('target').properties.get('storeId') * 1
        this.storeSelectAction(id)
    },
    mapDeliveryInit: function () {
        if (this.mapDeliveryInited) return
        const initStoreData = this.options.storeData[49]
        this.ymaps.delivery.map = new ymaps.Map(this.tabDeliveryMap.prop('id'), {
            center: [initStoreData['GPS_S'], initStoreData['GPS_N']],
            zoom: 10,
            controls: ['geolocationControl', 'searchControl']
        })
        this.ymaps.delivery.searchControl = this.ymaps.delivery.map.controls.get('searchControl')
        this.ymaps.delivery.searchControl.options.set({noPlacemark: true, placeholderContent: 'Введите адрес доставки', useMapBounds: true})
        this.ymaps.delivery.point = new ymaps.GeoObject({
            geometry: {type: 'Point'},
            properties: {iconCaption: 'Адрес'}
        }, {
            preset: 'islands#blackDotIconWithCaption',
            draggable: true,
            iconCaptionMaxWidth: '215'
        })
        this.ymaps.delivery.map.geoObjects.add(this.ymaps.delivery.point)
        this.ymaps.delivery.placemark = {}
        for (let id in this.options.storeData){
            let store = this.options.storeData[id]
            let placemark = new ymaps.Placemark([store['GPS_S'], store['GPS_N']], {storeId: id})
            placemark.events.add('click', this.placemarkClickHandler, this)
            this.ymaps.delivery.placemark[id] = placemark
            this.ymaps.delivery.map.geoObjects.add(placemark)
        }

        this.ymaps.delivery.zones = ymaps.geoQuery(this.options.geodata).addToMap(this.ymaps.delivery.map)
        this.ymaps.delivery.zones.each(function (obj) {
            obj.options.set({
                fillColor: obj.properties.get('fill'),
                fillOpacity: obj.properties.get('fill-opacity'),
                strokeColor: obj.properties.get('stroke'),
                strokeWidth: obj.properties.get('stroke-width'),
                strokeOpacity: obj.properties.get('stroke-opacity')
            });
            obj.properties.set('balloonContent', obj.properties.get('description'));
        });

        this.ymaps.delivery.searchControl.events.add('resultshow', $.proxy(this.searchResultHandler, this))
        //this.ymaps.delivery.map.controls.get('geolocationControl').add('locationchange', $.proxy(this.locationChangeHandler, this))

        this.mapDeliveryInited = true
    },

    searchResultHandler: function (e) {
        let objectSearch = this.ymaps.delivery.searchControl.getResultsArray()[e.get('index')]
        const resultCoords = objectSearch.geometry.getCoordinates();
        let polygon = this.ymaps.delivery.zones.searchContaining(resultCoords).get(0)
        debugger
        if (polygon){
            this.ymaps.delivery.zones.setOptions('fillOpacity', 0.4)
            polygon.options.set('fillOpacity', 0.8)
            this.ymaps.delivery.point.geometry.setCoordinates(resultCoords)
            this.ymaps.delivery.point.options.set('iconColor', polygon.properties.get('fill'))
            if (typeof(objectSearch.getThoroughfare) === 'function') {
                this.setData(objectSearch, polygon)
            } else {
                // Если вы не хотите, чтобы при каждом перемещении метки отправлялся запрос к геокодеру,
                // закомментируйте код ниже.
                //this.ymaps.delivery.map.geocode(coords, {results: 1}).then($.proxy(this.setData, this));
            }

        }else{
            this.ymaps.delivery.zones.setOptions('fillOpacity', 0.4)
            this.ymaps.delivery.point.geometry.setCoordinates(resultCoords)
            this.ymaps.delivery.point.properties.set({
                iconCaption: 'Доставка транспортной компанией',
                balloonContent: 'Cвяжитесь с оператором',
                balloonContentHeader: ''
            })
        }

    },
    setData: function(object, polygon) {
        var address = [object.getThoroughfare(), object.getPremiseNumber(), object.getPremise()].join(' ');
        if (address.trim() === '') {
            address = object.getAddressLine();
        }
        var price = polygon.properties.get('description');
        //price = price.match(/<strong>(.+)<\/strong>/)[1];
        deliveryPoint.properties.set({
            iconCaption: address,
            balloonContent: address,
            balloonContentHeader: 'test'
        });
    },
    locationChangeHandler: function (e) {
        debugger;
    },

    storeItemInit: function () {
        this.tabPickupItems[this.options.currentStoreId].addClass('selectedStore')
        this.tabPickupList.on('click', $.proxy(this.storeListClickHandler, this))
    },
    storeListClickHandler: function (el) {
        const storeId = $(el.currentTarget).attr('data-id') * 1
        this.storeSelectAction(storeId)
    },
    storeSelectAction: function (storeId) {
        const storeData = this.options.storeData[storeId]
        const center = [storeData['GPS_S'], storeData['GPS_N']]
        this.ymaps.pickup.map.setCenter(center, 10, {
            duration: 500
        })
        this.tabPickupList.removeClass('selectedStore')
        this.tabPickupItems[storeId].addClass('selectedStore')
    }
}