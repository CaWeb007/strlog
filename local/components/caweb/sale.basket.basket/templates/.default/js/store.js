;(function(){
    'use strict';

    BX.namespace('BX.Sale.BasketStore');

    BX.Sale.BasketStore = function(component)
    {
        this.component = component;
        this.storeFilter = $('#bx_delivery_filter');
        this.storeName = this.storeFilter.find('.basket-items-list-header-delivery-city-name');
        this.storeToggle = this.storeFilter.find('.basket-items-list-header-delivery-city-change-toggle');
        this.storeList = this.storeFilter.find('.basket-items-list-header-delivery-city-list');
        this.storeListWrapper = this.storeFilter.find('.basket-items-list-header-delivery-city-wrapper');
        this.storeListItem = this.storeListWrapper.children('.basket-items-list-header-delivery-city-item');
        this.itemsTable = $('#basket-item-table > tbody');
        this.itemsWrap = $('#basket-item-list');
        this.loader = this.itemsWrap.find('#load_overlay');
        this.init();
    };
    BX.Sale.BasketStore.prototype.init = function(){
        this.storeToggle.on('click', $.proxy(this.storeToggleAction, this));
        this.storeListItem.on('click', $.proxy(this.storeListItemAction, this));
    };
    BX.Sale.BasketStore.prototype.storeToggleAction = function(){
        if (this.storeList.hasClass('open'))
            this.storeList.removeClass('open')
        else
            this.storeList.addClass('open')
    };
    BX.Sale.BasketStore.prototype.storeListItemAction = function(event){
        this.changeStore(event);
        this.storeToggleAction();
    };
    BX.Sale.BasketStore.prototype.changeStore = function(event){
        var target = $(event.target),
            storeId = target.attr('data-store-id'),
            storeName = target.text(),
            oldStore = this.getCurrentStore();
        if (oldStore === storeId) return;
        this.component.actionPool.setStoreId(storeId);
        this.storeFilter.attr('data-store-id', storeId);
        this.storeName.text(storeName);
    };
    BX.Sale.BasketStore.prototype.getCurrentStore = function(){
        return this.storeFilter.attr('data-store-id');
    };
    BX.Sale.BasketStore.prototype.func = function(){};
})();