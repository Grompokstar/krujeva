Module.define(
	"GIS.UI.Layer.Animarkers",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.VehicleAnimarkers = Class(GIS.UI.Layer.Animarkers, {
			items: {},

			animationZoom: 14,

			initialize: function (map, options) {
				this.Parent(map);
			},

			destroy: function () {
				this.items = null;

				this.ParentCall();
			},

			getStyle: function (style, attributes) {
				return this.Parent(Util.merge({
					riseOnHover: true,
					iconUrl: this.getVehicleIconUrl(attributes),
					iconSize: this.getVehicleIconSize(attributes),
					title: attributes.num
				}, style), attributes);
			},

			getVehicleIconSize: function (attributes) {
				return [35, 35]
			},

			locate: function (id) {
				var markerId = this.items[id];

				if (markerId) {
					var item = this.markers[markerId];

					if (item) {
						this.map.setCenter(item.geog);
					}
				}
			},

			openPopup: function (id) {
				var markerId = this.items[id];

				if (markerId) {
					var item = this.markers[markerId];

					if (item.marker) {
						item.marker.openPopup();
					}
				}
			},

			courseImage: function (course, speed) {
				if (!speed) {
					return "standing";
				}

				if (course >= 337.5 || course < 22.5) {
					return "n";
				}

				if (course >= 22.5 && course < 67.5) {
					return "ne";
				}

				if (course >= 67.5 && course < 112.5) {
					return "e";
				}

				if (course >= 112.5 && course < 157.5) {
					return "se";
				}

				if (course >= 157.5 && course < 202.5) {
					return "s";
				}

				if (course >= 202.5 && course < 247.5) {
					return "sw";
				}

				if (course >= 247.5 && course < 292.5) {
					return "w";
				}

				if (course >= 292.5 && course < 337.5) {
					return "nw";
				}

				return "n";
			}
		});
	}
);
