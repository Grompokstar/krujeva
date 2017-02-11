Module.define(
	"Data.ServerSource",

	function () {

		NS("GIS.Data");

		GIS.Data.Plan = Class(Data.ServerSource, {
			url: "GIS/Web/Plan/"
		});
	}
);