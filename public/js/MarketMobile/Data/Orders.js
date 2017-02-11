Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.Orders = Class(KrujevaMobile.Data.ServerSource, {
			url: "/Orders/",

			addorder: function (item, callback) {
				return this.call("addorder", { item: JSON.stringify(item) }, callback);
			},

			mobilelisthistory: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilelisthistory", options, callback);
			},

			mobileitem: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobileitem", options, callback);
			},
		});
});
