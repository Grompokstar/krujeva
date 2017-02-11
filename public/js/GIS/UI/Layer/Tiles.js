Module.define(
	"GIS.UI.Layer.Base",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.Tiles = Class(GIS.UI.Layer.Base, {
			url: null,
			minZoom: 0,
			maxZoom: 18,
			tileSize: 256,

			layer: null,

			initialize: function (map, options) {
				this.Parent(map);

				this.override(["url", "minZoom", "maxZoom", "tileSize"], options);

				this.layer = L.tileLayer(this.url, {
					minZoom: this.minZoom,
					maxZoom: this.maxZoom,
					tileSize: this.tileSize
				});

				this.map.addLayer(this.layer);
			},

			destroy: function () {
				this.map.removeLayer(this.layer);

				this.layer = null;

				this.ParentCall();
			}
		});
	}
);
