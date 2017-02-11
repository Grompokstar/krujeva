Module.define(
	function () {
		NS("GIS");

		GIS.Geog = Static({
			point: function (lon, lat) {
				return {
					type: "Point",
					coordinates: [lon, lat]
				};
			},

			decimalToDMSString: function (value) {
				var d = parseInt(value);
				var md = Math.abs(value - d) * 60;
				var m = parseInt(md);
				var sd = (md - m) * 60;
				return d + "&deg; " + m + "&apos; " + sd.toFixed(2) + "&apos;&apos;";
			},

			bearing: function (lat1, lng1, lat2, lng2) {

				function radians(n) {
					return n * (Math.PI / 180);
				}

				function degrees(n) {
					return n * (180 / Math.PI);
				}

				lat1 = radians(lat1);
				lng1 = radians(lng1);
				lat2 = radians(lat2);
				lng2 = radians(lng2);

				var dLong = lng2 - lng1;

				var dPhi = Math.log(Math.tan(lat2 / 2.0 + Math.PI / 4.0) / Math.tan(lat1 / 2.0 + Math.PI / 4.0));

				if (Math.abs(dLong) > Math.PI) {
					if (dLong > 0.0)
						dLong = -(2.0 * Math.PI - dLong); else
						dLong = (2.0 * Math.PI + dLong);
				}

				return (degrees(Math.atan2(dLong, dPhi)) + 360.0) % 360.0;
			},

			compassBearing: function (lat1, lng1, lat2, lng2) {
				var bearing = this.bearing(lat1, lng1, lat2, lng2);

				var precision = 2;

				var point = '';

				switch (precision) {
					case 1: // 4 compass points
						switch (Math.round(bearing * 4 / 360) % 4) {
							case 0:
								point = 'N';
								break;
							case 1:
								point = 'E';
								break;
							case 2:
								point = 'S';
								break;
							case 3:
								point = 'W';
								break;
						}
						break;
					case 2: // 8 compass points
						switch (Math.round(bearing * 8 / 360) % 8) {
							case 0:
								point = 'N';
								break;
							case 1:
								point = 'NE';
								break;
							case 2:
								point = 'E';
								break;
							case 3:
								point = 'SE';
								break;
							case 4:
								point = 'S';
								break;
							case 5:
								point = 'SW';
								break;
							case 6:
								point = 'W';
								break;
							case 7:
								point = 'NW';
								break;
						}
						break;
					case 3: // 16 compass points
						switch (Math.round(bearing * 16 / 360) % 16) {
							case  0:
								point = 'N';
								break;
							case  1:
								point = 'NNE';
								break;
							case  2:
								point = 'NE';
								break;
							case  3:
								point = 'ENE';
								break;
							case  4:
								point = 'E';
								break;
							case  5:
								point = 'ESE';
								break;
							case  6:
								point = 'SE';
								break;
							case  7:
								point = 'SSE';
								break;
							case  8:
								point = 'S';
								break;
							case  9:
								point = 'SSW';
								break;
							case 10:
								point = 'SW';
								break;
							case 11:
								point = 'WSW';
								break;
							case 12:
								point = 'W';
								break;
							case 13:
								point = 'WNW';
								break;
							case 14:
								point = 'NW';
								break;
							case 15:
								point = 'NNW';
								break;
						}
						break;
				}

				return point.toLowerCase();
			}
		});
	}
);
