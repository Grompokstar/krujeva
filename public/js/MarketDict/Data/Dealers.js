Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Dealers = Class(KrujevaDict.Data.ServerSource, {
			url: "/Dealers/",

			pack: function (options, callback) {
				return this.call("pack", options, callback);
			}
		});
});
