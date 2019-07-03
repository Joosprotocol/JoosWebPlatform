let NotificationHelper = {

    map: {},
    values: {},

    _map: function() {
        this.map = {
            notificationDataDiv: $('#notification-data'),
        };
    },

    _values: function () {
        this.values = {
            list : this.map.notificationDataDiv.data('notification-list'),
        }
    },

    init: function() {
        let _this = this;
        this._map();
        this._values();

        if (this.map.notificationDataDiv.length !== 0) {
            _this.setAsRead();
        }
    },

    setAsRead: function () {
        let _this = this;
        $.ajax({
            url: '/notification/set-as-read',
            type: "POST",
            dataType: 'json',
            data: {
                'list': _this.values.list
            },
            success: function (data) {
            },
            error: function (data) {
            }
        });
    }
};

$(function() {
    NotificationHelper.init();
});
