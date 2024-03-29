var SignUpHelper = {
    map: {},

    _map: function () {
        var $this = this;
        $this.map = {
            selectRoleName: $('#usersignupform-rolename'),
            dataContainerSignUp: $('#signup-data-container'),

            divBorrowerMode: $('#signup-borrower-mode'),
        };
    },

    init: function () {
        var $this = this;
        $this._map();
        $this.map.selectRoleName.change(function() { return $this.changeRoleName(this); });
    },

    changeRoleName: function(select) {
        let roleNames = this.map.dataContainerSignUp.data().role_names;
        this.closeAllSignUpRoleDivs();
        if ($(select).val() == roleNames.borrower_name) {
            this.map.divBorrowerMode.show(200);
        }

    },

    closeAllSignUpRoleDivs: function () {
        this.map.divBorrowerMode.hide(200);
    }

};

$(function () {
    SignUpHelper.init();
});
