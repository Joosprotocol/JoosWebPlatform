var CollateralCalcHelper = {
    map: {},
    values: {},
    precision: 100000,

    _map: function() {
        this.map = {
            amountInput: $('#collateralcreateform-amount'),
            currencyInput: $('#collateralcreateform-currency_type'),
            requiredAmountRangeInput: $('#collateral-required-amount-range'),
            requiredAmountInput: $('#collateralcreateform-amountrequired'),
            createDataDiv: $('#collateral-create-data'),

        };
    },

    _values: function () {
        this.values = {
            requiredAmountMax : parseFloat(this.map.createDataDiv.data('required-amount-max')),
            requiredAmountMin : parseFloat(this.map.createDataDiv.data('required-amount-min')),
            currencyRates : this.map.createDataDiv.data('currency-rates'),
            lvr : this.map.createDataDiv.data('lvr')
        }
    },

    init: function() {
        let $this = this;
        this._map();
        this._values();
        this.map.amountInput.on('change', function() { return $this.changeAmount(this); });
        this.map.requiredAmountInput.on('change', function() { return $this.changeRequiredAmountInput(this); });

        if (this.map.requiredAmountRangeInput.length !== 0) {
            this.map.requiredAmountRangeInput.slider({
                stop: function( event, ui ) {$this.changeRequiredAmountRangeInput(this);}
            });
        }

        this.map.currencyInput.on('change', function() { return $this.changeRequiredAmountInput(this); });
        $this.map.requiredAmountInput.change();

    },

    changeRequiredAmountInput: function (element) {
        let requiredAmount = $(element).val();
        this.changeRequiredAmount(requiredAmount);
    },

    changeRequiredAmountRangeInput: function (element) {
        let requiredAmount = $(element).slider( "option", "value" );
        this.changeRequiredAmount(requiredAmount);
    },

    changeRequiredAmount:function (requiredAmount) {
        let fixedRequiredAmount = this.getFixedRequiredAmountByConditions(requiredAmount);
        this.setRequiredAmountFields(fixedRequiredAmount);
        let currency = this.map.currencyInput.val();
        this.setAmountFields(this.calculateAmount(fixedRequiredAmount, currency))

    },

    changeAmount: function(element){
        let amount = $(element).val();
        let currency = this.map.currencyInput.val();
        let requiredAmount = this.calculateRequiredAmount(amount, currency);
        let recalculatedAmount = this.calculateAmount(requiredAmount, currency);
        let fixedRequiredAmount = this.getFixedRequiredAmountByConditions(requiredAmount);

        this.setAmountFields(this.calculateAmount(fixedRequiredAmount, currency));

        this.setRequiredAmountFields(fixedRequiredAmount);
        console.log(recalculatedAmount, requiredAmount);
        $(element).val(recalculatedAmount);
    },

    setRequiredAmountFields: function (requiredAmount) {
        this.map.requiredAmountInput.val(requiredAmount);
        this.map.requiredAmountRangeInput.slider({'value' : requiredAmount});
    },

    setAmountFields: function (requiredAmount) {
        this.map.amountInput.val(requiredAmount);
    },

    calculateRequiredAmount: function (amount, currency) {
        return Math.floor(amount * this.getCurrencyRate(currency) * this.precision) / this.precision;
    },

    calculateAmount: function (requiredAmount, currency) {
        return Math.ceil(requiredAmount / this.getCurrencyRate(currency) * this.precision) / this.precision;
    },

    getCurrencyRate: function (currency) {
        return this.values.currencyRates[currency] * this.values.lvr / 100;
    },

    getFixedRequiredAmountByConditions: function (requiredAmount) {
        if (Number.isNaN(parseInt(requiredAmount))) {
            return this.values.requiredAmountMin;
        }
        if (requiredAmount >= this.values.requiredAmountMax) {
            return this.values.requiredAmountMax;
        }
        if (requiredAmount <= this.values.requiredAmountMin) {
            return this.values.requiredAmountMin;
        }
        return parseInt(requiredAmount);
    },

};

$(function() {
    CollateralCalcHelper.init();
});