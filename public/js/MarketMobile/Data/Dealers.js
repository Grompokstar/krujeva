Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.Dealers = Class(KrujevaMobile.Data.ServerSource, {
			url: "/Dealers/",

			mobilelist: function (options, callback) {

				options = Util.object(options);

				options = Util.merge(options, {methodType: 'GET'});

				return this.call("mobilelist", options, callback);
			},

		});
});
