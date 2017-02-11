Module.define(
	"City.Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Cities = Class(City.Data.ServerSource, {
			url: "City/Web/Cities/"
		});
});
