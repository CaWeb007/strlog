(function(){
    if (window.MainRegisterLegalComponent)
        return;
    window.MainRegisterLegalComponent = function(arParams){
        this.container = $('#bx_main_register_legal');
        this.form = this.container.find('form');
        this.kppContainer = this.form.find('#UF_KPP_CONTAINER');
        this.kppInput = this.kppContainer.find('input[name=UF_KPP]');
        this.notUseKpp = this.form.find('#not_use_kpp');
        BX.ready(BX.delegate(this.init, this));
    };
    window.MainRegisterLegalComponent.prototype = {
        init: function () {
            this.notUseKppChange();
            this.sEventInit();
        },
        sEventInit: function () {
            this.notUseKpp.change(BX.delegate(this.notUseKppChange, this));
        },
        notUseKppChange: function (e) {
            var notUseKpp = this.notUseKpp.prop('checked');
            if (notUseKpp) {
                this.kppInput.val("");
                this.kppContainer.hide(300);
            }else{
                this.kppContainer.show(300);
            }
        },
    };
})(window);
