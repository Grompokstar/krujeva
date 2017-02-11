Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Users = Class(KrujevaDict.Data.ServerSource, {
			url: "/Users/",

			login: function (options, callback) {
				return this.call("login", options, callback);
			},

			logout: function (options, callback) {
				return this.call("logout", options, callback);
			},

			alive: function (options, callback) {
				return this.call("alive", options, callback);
			},

			changestatus: function (options, callback) {
				return this.call("changestatus", options, callback);
			},
		});
});
