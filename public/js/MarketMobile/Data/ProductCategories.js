Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.ProductCategories = Class(KrujevaMobile.Data.ServerSource, {
			url: "/ProductCategories/",

			mobilelist: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilelist", options, callback);
			},

			mobilegift: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilegift", options, callback);
			},

		});
});
