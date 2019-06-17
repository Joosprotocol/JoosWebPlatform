var CollateralLoanPaymentHelper = {

    map: {},
    values: {},

    _map: function() {
        this.map = {
            paymentDataDiv: $('#collateral-loan-payment-data'),
            refreshPaymentButton: $('.collateral-loan-view a.js-refresh-payment'),
            withdrawButton: $('.collateral-loan-view a.js-withdraw'),
            paymentErrorLine: $('.collateral-loan-payments-block .error-line'),
        };
    },

    _values: function () {
        this.values = {
            collateralLoanHashId : this.map.paymentDataDiv.data('collateral-loan-hash-id'),
        }
    },

    init: function() {
        let _this = this;
        this._map();
        this._values();
        _this.map.refreshPaymentButton.on('click', function(){_this.sendPaymentCheckRequest()});
        _this.map.withdrawButton.on('click', function(){_this.sendWithdrawRequest()});

    },

    sendPaymentCheckRequest: function () {
        let _this = this;

        if (_this.map.refreshPaymentButton.hasClass('loading')) {
            return;
        }
        _this.map.refreshPaymentButton.addClass('loading');

        $.ajax({
            url: '/collateral/loan-refresh-payment',
            type: "POST",
            dataType: 'json',
            data: {
                'hashId': _this.values.collateralLoanHashId
            },
            success: function (data) {
                if (parseInt(data.paidAmount) > 0) {
                    window.location.replace("/collateral/loan/" + _this.values.collateralLoanHashId);
                }
                _this.map.refreshPaymentButton.removeClass('loading');

            },
            error: function (data) {
                _this.map.refreshPaymentButton.removeClass('loading');
            }
        });
    },

    sendWithdrawRequest: function () {
        let _this = this;

        if (_this.map.withdrawButton.hasClass('loading')) {
            return;
        }
        _this.map.withdrawButton.addClass('loading');

        $.ajax({
            url: '/collateral/loan-withdraw',
            type: "POST",
            dataType: 'json',
            data: {
                'hashId': _this.values.collateralLoanHashId
            },
            success: function (data) {
                if (JSON.parse(data.isWithdrawn) === true) {
                    window.location.replace("/collateral/loan/" + _this.values.collateralLoanHashId);
                } else {
                    _this.map.paymentErrorLine.html(data.errorMessage);
                }
                _this.map.withdrawButton.removeClass('loading');

            },
            error: function (data) {
                _this.map.withdrawButton.removeClass('loading');
            }
        });
    }
};

$(function() {
    CollateralLoanPaymentHelper.init();
});
