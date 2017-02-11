Module.define(
	"GIS.Geog",
	"GIS.Leaflet",
	"GIS.UI.Map",
	"GIS.UI.Layer.Base",
	"GIS.UI.Layer.Markers",
	"GIS.UI.Layer.Animarkers",
	"GIS.UI.Layer.Tiles",
	"GIS.UI.Layer.OSM",
	"GIS.UI.Layer.GOSM",

	function () {
		NS("GIS");

		GIS.Module = true;
	}
);
