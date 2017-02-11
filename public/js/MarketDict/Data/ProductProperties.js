Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.ProductProperties = Class(KrujevaDict.Data.ServerSource, {
			url: "/ProductProperties/",

			categories: function (options, callback) {
				return this.call("categories", options, callback);
			},

			properties: function (options, callback) {
				return this.call("properties", options, callback);
			}
		});
});
