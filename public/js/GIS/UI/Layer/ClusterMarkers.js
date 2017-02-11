Module.define(
	"GIS.UI.Layer.Markers",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.ClusterMarkers = Class(GIS.UI.Layer.Markers, {
			cluster: null,

			initialize: function (map, base, options) {
				this.Parent(map, base, options);

				this.groupLayer = new L.MarkerClusterGroup({ showCoverageOnHover: false, disableClusteringAtZoom: 15 });
				this.map.map.addLayer(this.groupLayer);

				this.bounds = this.map.map.getBounds();

				this.map.on("move.end", this.onMapMoveEnd, this);
				this.map.on("zoom.end", this.onMapZoomEnd, this);
			}
		});
	}
);
