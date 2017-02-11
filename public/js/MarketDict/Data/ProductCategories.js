Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.ProductCategories = Class(KrujevaDict.Data.ServerSource, {
			url: "/ProductCategories/",

			tree: function (options, callback) {
				return this.call("tree", options, callback);
			}
		});
});
