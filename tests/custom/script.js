function JSEcosystem(){
    this.conteiner = $('#ecosystem-wrapper');
    this.sliderContainer = this.conteiner.find('.ecosystem-slider');
    this.allWidgets = this.conteiner.find('.widget');
    this.widgetPopup = this.conteiner.find('.ecosystem-popup');
    this.window_$ = $(window);
    this.initSlider = function () {
        this.sliderContainer.flexslider({
            animation: "slide",
            animationLoop: false,
            itemWidth: 120,
            itemMargin: 10,
            slideshow: false
        })
    }
    this.showWidgetPopup = function (widget) {
        var wId = widget.data('id');
        if (widget.hasClass('widget__open')) return;
        var list = widget.find('#widget-submenu').clone();
        this.widgetPopup.find('.ecosystem-popup__body').append(list);
        this.widgetPopup.css('display', 'block');
        this.widgetPopup.position(this.getPositionSettings(widget));
        this.window_$.on('resize.popupRepositon', null, {'widget': widget}, $.proxy(this.popupReposition, this));
        this.widgetPopup.css('opacity', 1);
        $(document).on('mouseup.closerWidget__' + wId, null, {'popup': this.widgetPopup, 'widget': widget}, $.proxy(this.hideWidgetPopup, this));
        widget.removeClass('widget__close');
        widget.addClass('widget__open');
    }
    this.popupReposition = function (event) {
        this.widgetPopup.position(this.getPositionSettings(event.data.widget));
    }
    this.popupPosition = function (e, v) {
        var $_this = $(this);
        var left = 0;
        var top = e.top + 10;
        if (v.horizontal === 'left') left = e.left + 6;
        else left = e.left - 42;
        $_this.css('top', top);
        $_this.css('left', left);
        $_this.removeClass('popup_widget_right');
        $_this.removeClass('popup_widget_left');
        $_this.addClass('popup_widget_' + v.horizontal);
    }
    this.getPositionSettings = function (widget){
        return {
            my: 'right center',
            of: widget.find('.widget_toggle-icon-wrapper'),
            using: this.popupPosition,
            within: this.conteiner,
            collision: 'flip flip'
        };
    }
    this.hideWidgetPopup = function (event){
        if (event.data.widget.hasClass('widget__close')) return;
        if ($(event.target).closest(event.data.popup).length) {
            return;
        }
        if ($(event.target).closest(event.data.widget).length) {
            return;
        }
        var wId = event.data.widget.data('id');
        event.data.popup.css('display', 'none');
        event.data.popup.css('opacity', 0);
        event.data.popup.find('.ecosystem-popup__body').empty();
        $(document).off('mouseup.closerWidget__' + wId);
        this.window_$.off('resize.popupRepositon');
        event.data.widget.removeClass('widget__open');
        event.data.widget.addClass('widget__close');
    }
    this.widgetClickEvent = function (event) {
        var widget = event.data;
        var href = widget.find('#widget-href').attr('href');
        if (widget.hasClass('widget-with_submenu')){
            this.showWidgetPopup(widget);
        }
        else if(href)
            window.location.href = href;
    }
    this.initWidgetClickEvents = function () {
        var widget = [], i, l = this.allWidgets.length;
        for (i = 0; i < l; i++){
            widget = $(this.allWidgets[i]);
            widget.addClass('widget__close');
            widget.on('click', null, widget, $.proxy(this.widgetClickEvent, this));
        }
    }
    this.initPopup = function () {
        this.widgetPopup.find('.ecosystem-popup__close').on('mouseup', $.proxy(this.closeWidgetAction, this))
    }
    this.closeWidgetAction = function(event){
        var wId, popup, widget;
        popup = this.widgetPopup;
        wId = popup.data('widgetid');
        widget = event.data.widget;



        event.data.popup.css('display', 'none');
        event.data.popup.css('opacity', 0);
        event.data.popup.find('.ecosystem-popup__body').empty();
        $(document).off('mouseup.closerWidget__' + wId);
        this.window_$.off('resize.popupRepositon');
        event.data.widget.removeClass('widget__open');
        event.data.widget.addClass('widget__close');
    }
    this.init = function () {
        this.initSlider();
        this.initPopup();
        this.initWidgetClickEvents();
    }
    $(document).ready($.proxy(this.init, this));
}