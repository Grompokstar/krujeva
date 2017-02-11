Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Cities = Class(KrujevaDict.Data.ServerSource, {
			url: "/Cities/"
		});
});
