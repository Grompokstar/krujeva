Module.define(
	"KrujevaDealer.Data.ServerSource",

	function () {
		NS("KrujevaDealer.Data");

		KrujevaDealer.Data.Dealers = Class(KrujevaDealer.Data.ServerSource, {
			url: "/Dealers/",

			regions: function (options, callback) {
				return this.call("regions", options, callback);
			}

		});
});