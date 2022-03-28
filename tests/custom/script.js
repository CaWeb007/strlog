function JSEcosystem(){
    this.conteiner = $('#ecosystem-wrapper');
    this.sliderContainer = this.conteiner.find('.ecosystem-slider');
    this.allWidgets = this.conteiner.find('.widget');
    this.widgetPopup = this.conteiner.find('.ecosystem-popup');
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
        this.widgetPopup.append(list);
        this.widgetPopup.css('display', 'block');
        this.widgetPopup.position({
            my: 'right',
            at: 'right',
            of: widget
        });
        $(document).on('mouseup.closerWidget__' + wId, null, {'popup': this.widgetPopup, 'widget': widget}, $.proxy(this.hideWidgetPopup, this));
        widget.removeClass('widget__close');
        widget.addClass('widget__open');
    }
    this.hideWidgetPopup = function (event){
        var wId = event.data.widget.data('id');
        if (event.data.widget.hasClass('widget__close')) return;
        if ($(event.target).closest(event.data.popup).length) {
            return;
        }
        if ($(event.target).closest(event.data.widget).length) {
            return;
        }
        event.data.popup.css('display', 'none');
        event.data.popup.empty();
        $(document).off('mouseup.closerWidget__' + wId);
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
        
    }
    this.init = function () {
        this.initSlider();
        this.initWidgetClickEvents();
    }
    $(document).ready($.proxy(this.init, this));
}