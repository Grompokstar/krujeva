Module.define(
	"KrujevaBonus.Data.ServerSource",

	function () {
		NS("KrujevaBonus.Data");

		KrujevaBonus.Data.Users = Class(KrujevaBonus.Data.ServerSource, {
			url: "/Users/",

			forgotpassword: function (options, callback) {
				return this.call("forgotpassword", options, callback);
			},

			bonuslogin: function (options, callback) {
				return this.call("bonuslogin", options, callback);
			},

			logout: function (options, callback) {
				return this.call("logout", options, callback);
			}
		});
});
