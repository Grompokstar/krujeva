Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.Products = Class(KrujevaMobile.Data.ServerSource, {
			url: "/Products/",

			mobilelist: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilelist", options, callback);
			},

			mobilelistgift: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilelistgift", options, callback);
			},

			mobileitem: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobileitem", options, callback);
			},

			mobilesearch: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilesearch", options, callback);
			},


		});
});
