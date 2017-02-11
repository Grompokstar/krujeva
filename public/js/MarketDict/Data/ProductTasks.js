Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.ProductTasks = Class(KrujevaDict.Data.ServerSource, {
			url: "/ProductTasks/",

			tree: function (options, callback) {
				return this.call("tree", options, callback);
			}
		});
});
