Module.define(
	"KrujevaDealer.Data.ServerSource",

	function () {
		NS("KrujevaDealer.Data");

		KrujevaDealer.Data.Orders = Class(KrujevaDealer.Data.ServerSource, {
			url: "/Orders/",

			dealerlist: function (options, callback) {
				return this.call("dealerlist", options, callback);
			},

            dealerget: function (options, callback) {
                return this.call("dealerget", options, callback);
            },

			dealercancel: function (options, callback) {
				return this.call("dealercancel", options, callback);
			},

			dealerconfirm: function (options, callback) {
				return this.call("dealerconfirm", { item: JSON.stringify(options) }, callback);
			}

		});
});