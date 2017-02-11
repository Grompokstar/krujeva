Module.define(
	"GIS.UI.Layer.Tiles",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.OSM = Class(GIS.UI.Layer.Tiles, {
			url: "/osm/{z}/{x}/{y}.png"
		});
	}
);
