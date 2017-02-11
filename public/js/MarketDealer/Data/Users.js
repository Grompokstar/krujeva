Module.define(
	"KrujevaDealer.Data.ServerSource",

	function () {
		NS("KrujevaDealer.Data");

		KrujevaDealer.Data.Users = Class(KrujevaDealer.Data.ServerSource, {
			url: "/Users/",

			forgotpassword: function (options, callback) {
				return this.call("forgotpassword", options, callback);
			},

			dealerlogin: function (options, callback) {
				return this.call("dealerlogin", options, callback);
			},

			logout: function (options, callback) {
				return this.call("logout", options, callback);
			}
		});
});
