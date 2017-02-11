Module.define(
	"KrujevaBonus.Data.ServerSource",

	function () {
		NS("KrujevaBonus.Data");

		KrujevaBonus.Data.Bonuss = Class(KrujevaBonus.Data.ServerSource, {
			url: "/Bonuss/",

			regions: function (options, callback) {
				return this.call("regions", options, callback);
			}

		});
});