Module.define(
	"Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Users = Class(Data.ServerSource, {
			url: "City/Web/Users/",

			login: function (options, callback) {
				return this.call("login", options, callback);
			},

			logout: function (options, callback) {
				return this.call("logout", options, callback);
			},

			password: function (options, callback) {
				return this.call("password", options, callback);
			},

			alive: function (options, callback) {
				return this.call("alive", options, callback);
			},

			changePeriod: function (options, callback) {
				return this.call("changePeriod", options, callback);
			},

			profile: function (options, callback) {
				return this.call("profile", options, callback);
			}
		});
});
