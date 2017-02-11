Module.define(
	"Data.ServerSource",

	function () {
		NS("OSM.Data");

		OSM.Data.GeoObjects = Class(Data.ServerSource, {
			url: "OSM/Web/GeoObjects/",

			nearest: function (options, callback) {
				return this.call("nearest", options, callback);
			}
		});
	});
