Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.Users = Class(KrujevaMobile.Data.ServerSource, {
			url: "/Users/",

			registration: function (options, callback) {
				return this.call("registration", options, callback);
			},

			passwordverify: function (options, callback) {
				return this.call("passwordverify", options, callback);
			},

			alivemobile: function (options, callback) {
				return this.call("alivemobile", options, callback);
			},

			mobilelogin: function (options, callback) {
				return this.call("mobilelogin", options, callback);
			},

			mobilebonus: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilebonus", options, callback);
			},

			forgotpassword: function (options, callback) {
				return this.call("forgotpassword", options, callback);
			},

		});
});
