Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Brands = Class(KrujevaDict.Data.ServerSource, {
			url: "/Brands/",

			tree: function (options, callback) {
				return this.call("tree", options, callback);
			}
		});
});
