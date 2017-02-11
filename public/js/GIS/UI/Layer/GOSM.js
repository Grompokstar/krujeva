Module.define(
	"GIS.UI.Layer.Tiles",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.GOSM = Class(GIS.UI.Layer.Tiles, {
			//url: "/tiles/{z}/{x}/{y}.png",
			url: "http://tile.tatar.ru/1024/{z}/{x}/{y}.png",
			minZoom: 2,
			tileSize: 1024
		});
	}
);
