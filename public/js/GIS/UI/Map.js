Module.define(
	"UI.Widget.Base",
	"GIS.Geog",
	"GIS.Leaflet",

	function () {
		NS("GIS.UI");

		GIS.UI.Map = Class(UI.Widget.Base, {
			template: "GIS/Map/Map",

			map: null,
			controls: {},

			destroy: function () {
				if (this.map) {
					this.map.remove();
				}

				this.ParentCall();
			},

			invalidate: function () {
				if (this.map) {
					this.map.invalidateSize();
				}
			},

			render: function () {
				var self = this;

				this.ParentCall();

				var options = {};

				if (this.options && this.options.map) {
					options = this.options.map;
				}

				this.map = L.map(this.element, options);

				this.map.on("click", function (args) {
					self.emit("click", args);
				});

				this.map.on("moveend", function (args) {
					self.emit("move.end", args);
				});

				this.map.on("zoomend", function (args) {
					self.emit("zoom.end", args);
				});

				return this;
			},

			align: function () {
				this.ParentCall();

				this.invalidate();
			},

			addLayer: function (layer) {
				if (!this.map.hasLayer(layer)) {
					this.map.addLayer(layer);
				}

				return this;
			},

			removeLayer: function (layer) {
				return this.map.removeLayer(layer);
			},

			getBounds: function () {
				return this.map.getBounds();
			},

			getZoom: function () {
				return this.map.getZoom();
			},

			setCenter: function (geog, zoom, options) {
				if (this.map) {
					this.map.setView(GIS.Leaflet.fromGeog(geog), zoom, options);
				}
			},

			setZoom: function (zoom) {
				if (this.map) {
					this.map.setZoom(zoom);
				}
			},

			addControl: function (name, controlClass, options) {
				var self = this;

				this.controls[name] = new controlClass(this, options);

				this.controls[name].on("destroy", function () {
					delete self.controls[name];
				});
			},
		});
	}
);
