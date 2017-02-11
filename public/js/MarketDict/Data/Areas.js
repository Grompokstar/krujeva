Module.define(
	"KrujevaDict.Data.ServerSource",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.Areas = Class(KrujevaDict.Data.ServerSource, {
			url: "/Areas/"
		});
});
