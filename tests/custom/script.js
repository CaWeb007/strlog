function JSEcosystem(){
    this.conteiner = $('#ecosystem-wrapper');
    this.sliderContainer = this.conteiner.find('.ecosystem-slider');
    this.allWidgets = this.conteiner.find('.widget');
    this.widgetPopup = this.conteiner.find('.ecosystem-popup');
    this.widgetPopupOpenFlag = false;
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
        //widget.off('click');
        if (this.widgetPopupOpenFlag === true) return;
        var list = widget.find('#widget-submenu').clone();
        this.widgetPopup.append(list);
        //var widgetOffset = widget.offset();
        //this.widgetPopup.offset(widgetOffset);
        this.widgetPopup.css('display', 'block');
        this.widgetPopup.position({
            my: 'right',
            at: 'right',
            of: widget
        });
        $(document).on('click', null, {'popup': this.widgetPopup, 'widget': widget}, $.proxy(this.hideWidgetPopup, this));
        this.widgetPopupOpenFlag = true;
    }
    this.hideWidgetPopup = function (event){
        if (this.widgetPopupOpenFlag === false) return;
        if ($(event.target).closest(event.data.popup).length) {
            return;
        }
        if ($(event.target).closest(event.data.widget).length) {
            return;
        }
        //event.data.popup.css('top', '0px');
        //event.data.popup.css('left', '0px');
        event.data.popup.css('display', 'none');
        event.data.popup.empty();
        $(document).off('click', null, $.proxy(this.hideWidgetPopup, this));
        //event.data.widget.on('click', null, event.data.widget, $.proxy(this.widgetClickEvent, this))
        this.widgetPopupOpenFlag = false;
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