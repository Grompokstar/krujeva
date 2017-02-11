Module.define(
	"GIS.UI.Layer.Base",
	"System.NumSeq",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.Markers = Class(GIS.UI.Layer.Base, {

			markers: {},

			groupLayer: null,

			bounds: null,

			icons: {},

			filterCallback: null,

			popupTemplate: null,

			initialize: function (map, options) {
				this.Parent(map);

				this.override(["popupTemplate"], options);

				if (!this.groupLayer) {
					this.groupLayer = new L.FeatureGroup();
				}

				this.map.map.addLayer(this.groupLayer);

				this.bounds = this.map.getBounds();

				this.map.on("move.end", this.onMapMoveEnd, this);
				this.map.on("zoom.end", this.onMapZoomEnd, this);
			},

			destroy: function () {
				var self = this;

				this.map.off("move.end", this.onMapMoveEnd, this);
				this.map.off("zoom.end", this.onMapZoomEnd, this);

				this.removeMarkers();
				this.markers = null;

				this.icons = null;

				this.ParentCall();
			},

			removeMarkers: function () {
				var self = this;

				Util.each(this.markers, function (item) {
					if (item.marker) {
						self.groupLayer.removeLayer(item.marker);
					}
				});

				this.markers = {};
			},

			insert: function (geog, style, attributes) {
				var id = System.NumSeq.next("GIS.UI.Layer.Markers");
				var latlng = GIS.Leaflet.fromGeog(geog);

				var item = {
					id: id,
					latlng: latlng,
					geog: geog,
					style: style,
					attributes: attributes,
					marker: null
				};

				this.updateMarker(item);

				this.markers[id] = item;

				return item;
			},

			update: function (id, style, attributes) {
				var item = this.markers[id];

				if (!item) {
					return null;
				}

				if (item.marker) {
					this.redrawMarker(item.marker, style, attributes);
				}

				item.style = style;
				item.attributes = attributes;

				return item;
			},

			remove: function (id) {
				var item = this.markers[id];

				if (!item) {
					return null;
				}

				if (item.marker) {
					item.marker = this.eraseMarker(item.marker);
				}

				delete this.markers[id];

				return null;
			},

			move: function (id, geog) {
				var item = this.markers[id];

				if (!item) {
					return null;
				}

				var latlng = GIS.Leaflet.fromGeog(geog);

				item.geog = geog;
				item.latlng = latlng;

				if (item.marker) {
					if (this.isFiltered()) {
						item.marker = this.moveMarker(item.marker, latlng);
					} else {
						item.marker = this.eraseMarker(item.marker);
					}
				} else {
					this.updateMarker(item);
				}

				return item;
			},

			filter: function (callback) {
				this.filterCallback = callback;

				this.updateMarkers();
			},

			refilter: function () {
				this.updateMarkers();
			},

			isFiltered: function (item) {
				if (!this.filterCallback) {
					return true;
				}

				return this.filterCallback(item.attributes);
			},

			findMarker: function (marker) {
				var result = null;

				Util.each(this.markers, function (item) {
					if (item.marker && item.marker == marker) {
						result = item;
						return false;
					}
				});

				return result;
			},

			drawMarker: function (latlng, style, attributes) {
				var marker = null;

				marker = L.marker(latlng, this.getStyle(style, attributes));
				this.groupLayer.addLayer(marker);


				if (this.popupTemplate) {
					this.bindPopup(marker, attributes);
				}

				return marker;
			},

			redrawMarker: function (marker, style, attributes) {
				this.setStyle(marker, this.getStyle(style, attributes));

				return marker;
			},

			moveMarker: function (marker, latlng) {
				if (this.bounds.contains(latlng)) {
					if (!marker.getLatLng().equals(latlng)) {
						marker.setLatLng(latlng);
					}

					return marker;
				} else {
					this.eraseMarker(marker);

					return null;
				}
			},

			eraseMarker: function (marker) {
				this.groupLayer.removeLayer(marker);

				return null;
			},

			updateMarker: function (item) {
				var contains = this.bounds.contains(item.latlng);
				var allowed = this.isFiltered(item);

				if (contains && !item.marker && allowed) {
					item.marker = this.drawMarker(item.latlng, item.style, item.attributes);
				} else if (item.marker && (!contains || !allowed)) {
					item.marker = this.eraseMarker(item.marker);
				}

				return item.marker;
			},

			updateMarkers: function () {
				var self = this;

				Util.each(this.markers, function (item) {
					self.updateMarker(item);
				});
			},

			setStyle: function (marker, style) {
				if (style.icon) {
					marker.setIcon(style.icon);
				}
			},

			getStyle: function (style, attributes) {
				var markerStyle = {};

				style = Util.object(style);

				if (style.iconUrl) {
					markerStyle.icon = this.getIcon(style, attributes);
				}

				if (style.riseOnHover !== undefined) {
					markerStyle.riseOnHover = style.riseOnHover;
				}

				if (style.title !== undefined) {
					markerStyle.title = style.title;
				}

				return markerStyle;
			},

			getIcon: function (style, attributes) {
				if (!this.icons[style.iconUrl]) {
					this.icons[style.iconUrl] = this.createIcon(style, attributes);
				}

				return this.icons[style.iconUrl];
			},

			createIcon: function (style, attributes) {
				var options = {};

				if (style.iconUrl) {
					options.iconUrl = style.iconUrl;
				}

				if (style.iconSize) {
					options.iconSize = style.iconSize;
				}

				return L.icon(options);
			},

			show: function () {
				if (!this.map.map.hasLayer(this.groupLayer)) {
					this.map.map.addLayer(this.groupLayer);
				}
			},

			hide: function () {
				if (this.map.map.hasLayer(this.groupLayer)) {
					this.map.map.removeLayer(this.groupLayer);
				}
			},

			bindPopup: function (feature, attributes) {
				var self = this;

				feature.bindPopup().on("popupopen", function (args) {
					self.updatePopupContent(args.popup, attributes);
				});
			},

			updatePopupContent: function (popup, attributes) {
				popup.setContent(UI.Template.render(this.popupTemplate, attributes ));

				this.emit("popup.update", {
					$content: $(popup._contentNode),
					data: attributes
				});
			},

			isVisible: function () {
				return this.map.map.hasLayer(this.groupLayer);
			},

			onMapMoveEnd: function (args) {
				this.bounds = this.map.getBounds();
				this.updateMarkers();
			},

			onMapZoomEnd: function (args) {
				this.bounds = this.map.getBounds();
				this.updateMarkers();
			}
		});
	}
);
