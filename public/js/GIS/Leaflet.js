Module.define(
	function () {
		NS("GIS");

		GIS.Leaflet = Static({
			fromGeog: function (geog) {
				var coordinates = [];
				var latlngs = [];

				if (geog && geog.lat && geog.lng) {
					return geog;
				}

				switch (geog.type.toLowerCase()) {
					case "point":
						return L.latLng(geog.coordinates[1], geog.coordinates[0]);
					case "polygon":
						coordinates = geog.coordinates[0];

						for (var i = 0; i < coordinates.length; i++) {
							var coordinate = coordinates[i];

							latlngs.push(L.latLng(coordinate[1], coordinate[0]));
						}

						return latlngs;
				}

				return null;
			},

			toGeog: function (latlng) {
				if (Util.isArray(latlng)) {
					return GIS.Geog.point(latlng[1], latlng[0]);
				} else {
					return GIS.Geog.point(latlng.lng, latlng.lat);
				}
			}
		});
	}
);
