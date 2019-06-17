var CollateralPaymentHelper = {

    map: {},
    values: {},

    _map: function() {
        this.map = {
            paymentDataDiv: $('#collateral-payment-data'),
            paidAmountSpan: $('.collateral-post .paid-amount'),
            refreshPaymentButton: $('.collateral-post-block a.js-refresh-post'),

        };
    },

    _values: function () {
        this.values = {
            collateralHashId : this.map.paymentDataDiv.data('collateral-hash-id'),
        }
    },

    init: function() {
        let _this = this;
        this._map();
        this._values();

        _this.map.refreshPaymentButton.on('click', function(){_this.sendPaymentCheckRequest()});


    },

    sendPaymentCheckRequest: function () {
        let _this = this;

        if (_this.map.refreshPaymentButton.hasClass('loading')) {
            return;
        }
        _this.map.refreshPaymentButton.addClass('loading');

        $.ajax({
            url: '/collateral/refresh-payment',
            type: "POST",
            dataType: 'json',
            data: {
                'hashId': _this.values.collateralHashId
            },
            success: function (data) {
                _this.map.paidAmountSpan.html(data.paidAmount);
                if (data.isAlreadyPaid === true) {
                    window.location.replace("/collateral/view/" + _this.values.collateralHashId);
                }
                _this.map.refreshPaymentButton.removeClass('loading');
            },
            error: function () {
                _this.map.refreshPaymentButton.removeClass('loading');
            }
        });
    }
};

$(function() {
    CollateralPaymentHelper.init();
});
