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
        debugger
        const initStoreData = this.options.storeData[this.options.currentStoreId]
        this.ymaps.pickup.map = new ymaps.Map(this.tabPickupMap.prop('id'), {
            center: [initStoreData['GPS_N'], initStoreData['GPS_S']],
            zoom: 10
        })
        this.mapPickupInited = true
    },
    mapDeliveryInit: function () {
        if (this.mapPickupInited) return
        this.ymaps.delivery.map = new ymaps.Map(this.tabDeliveryMap.prop('id'), {
            center: [55.76, 36.64],
            zoom: 10
        })
        this.mapDeliveryInited = true
    },
    storeItemInit: function () {
        this.tabPickupItems[this.options.currentStoreId].addClass('selectedStore')
        this.tabPickupList.on('click', $.proxy(this.storeListClick, this))
    },
    storeListClick: function (el) {
        let target = $(el.currentTarget)
        this.tabPickupList.removeClass('selectedStore')
        target.addClass('selectedStore')
    }
}