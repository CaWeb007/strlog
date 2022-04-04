function JSEcosystem(){
    this.conteiner = $('#ecosystem-wrapper');
    this.sliderContainer = this.conteiner.find('.ecosystem-slider');
    this.allWidgets = this.conteiner.find('.widget');
    this.widgetPopup = this.conteiner.find('.ecosystem-popup');
    this.window_$ = $(window);
    this.mobileView = false;
    this.sliderInited = false;
    this.popupCloseAction = function(event){
        var wId, widget;
        widget = event.data.widget;
        wId = widget.data('id');
        this.widgetPopup.css('opacity', 0);
        this.widgetPopup.css('display', 'none');
        widget.removeClass('widget__open');
        widget.addClass('widget__close');
        this.widgetPopup.find('.ecosystem-popup__body').empty();
        $(document).off('mouseup.autoClosePopup__' + wId);
        $(document).off('mouseup.popupCloseAction__' + wId);
        this.window_$.off('resize.popupReposition');
    }
    this.autoClosePopup = function (event){
        var widget = event.data.widget;
        if (widget.hasClass('widget__close')) return;
        if ($(event.target).closest(this.widgetPopup).length) return;
        if ($(event.target).closest(widget).length)  return;
        this.popupCloseAction(event)
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
    this.popupPositionMobile = function (e, v) {
        var $_this = $(this);
        $_this.css('top', 0);
        $_this.css('left', '');
        $_this.css('right', 0);
    }
    this.getPositionSettings = function (widget){
        if (!this.mobileView){
            this.widgetPopup.removeClass('mobile-view');
            return {
                my: 'right center',
                of: widget.find('.widget_toggle-icon-wrapper'),
                using: this.popupPosition,
                within: this.conteiner,
                collision: 'flip'
            };
        }else{
            if (!this.widgetPopup.hasClass('mobile-view')) this.widgetPopup.addClass('mobile-view');
            return {
                my: 'right top',
                of: this.window_$,
                using: this.popupPositionMobile,
                within: this.window_$,
                collision: 'none'
            };
        }
    }
    this.showWidgetPopup = function (widget) {
        var wId, list, title;
        if (widget.hasClass('widget__open')) return;
        list = widget.find('#widget-submenu').clone();
        this.widgetPopup.find('.ecosystem-popup__body').append(list);
        title = widget.find('.widget-title').text();
        this.widgetPopup.find('.ecosystem-popup__title').text(title);
        this.widgetPopup.css('display', 'block');
        this.widgetPopup.position(this.getPositionSettings(widget));
        this.window_$.on('resize.popupReposition', null, {'widget': widget}, $.proxy(this.popupReposition, this));
        this.widgetPopup.css('opacity', 1);
        wId = widget.data('id');
        $(document).on('mouseup.autoClosePopup__' + wId, null, {'widget': widget}, $.proxy(this.autoClosePopup, this));
        this.widgetPopup.find('.ecosystem-popup__close').on('mouseup.popupCloseAction__' + wId, null, {'widget': widget}, $.proxy(this.popupCloseAction, this))
        widget.removeClass('widget__close');
        widget.addClass('widget__open');
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
    this.initSlider = function () {
        if (this.sliderInited) return;
        this.sliderContainer.flexslider({
            animation: "slide",
            animationLoop: false,
            itemWidth: 120,
            itemMargin: 10,
            slideshow: false,
            keyboard: false,
            controlNav: false,
            directionNav: false,

        });
        this.sliderInited = true;
    }
    this.sliderEvent = function (event) {
        if (this.window_$.width() < 639){
            this.mobileView = true;
            this.initSlider();
        }else{
            this.mobileView = false;
        }
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
        this.widgetPopup.appendTo('body');
    }
    this.init = function () {
        this.initPopup();
        this.window_$.on('resize', $.proxy(this.sliderEvent, this));
        this.sliderEvent();
        this.initWidgetClickEvents();
    }
    $(document).ready($.proxy(this.init, this));
}