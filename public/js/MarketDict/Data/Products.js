Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Products = Class(KrujevaDict.Data.ServerSource, {
			url: "/Products/",

			pack: function (options, callback) {
				return this.call("pack", options, callback);
			}
		});
});
