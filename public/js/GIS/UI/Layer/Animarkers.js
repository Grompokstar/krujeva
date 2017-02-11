Module.define(
	"GIS.UI.Layer.Markers",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.Animarkers = Class(GIS.UI.Layer.Markers, {
			animation: true,
			animationTimeout: 50,
			animationFrames: 10,
			animationFollow: null,

			insert: function () {
				var marker = this.ParentCall();

				if (marker) {
					marker["timer"] = null;
					marker["destination"] = null;
				}

				return marker;
			},

			move: function (id, geog, callback) {
				var item = this.markers[id];

				if (!item) {
					return null;
				}

				var latlng = GIS.Leaflet.fromGeog(geog);

				if (item.marker) {
					item.marker = this.moveMarker(id, latlng, callback);
				} else {
					if (this.animationFollow == id) {
						this.map.map.setView(item.latlng, undefined, { animate: false });
					}

					item.marker = this.updateMarker(item);

					if (Util.isFunction(callback)) {
						callback();
					}
				}

				item.geog = geog;
				item.latlng = latlng;

				return item;
			},

			moveMarker: function (id, latlng, callback) {
				var self = this;
				var item = this.markers[id];
				var marker = item.marker;

				item["destination"] = latlng;

				if (!this.animation) {
					var result;

					if (this.isFiltered(item)) {
						result = this.Parent(marker, latlng);
					} else {
						if (item.marker) {
							item.marker = this.eraseMarker(item.marker);
						}
					}

					if (this.animationFollow == id) {
						this.map.map.setView(latlng, undefined, { animate: false });
					}

					if (Util.isFunction(callback)) {
						callback();
					}

					return result;
				} else {
					if (marker.getLatLng().equals(latlng)) {
						if (Util.isFunction(callback)) {
							callback();
						}
						
						return marker;
					}

					if (this.bounds.contains(latlng)) {
						var fromLatLng = marker.getLatLng();

						var count = this.animationFrames;
						var timeout = this.animationTimeout;

						var dx = (latlng.lng - fromLatLng.lng) / count;
						var dy = (latlng.lat - fromLatLng.lat) / count;
						var lat = fromLatLng.lat + dy;
						var lng = fromLatLng.lng + dx;

						marker.setLatLng([lat, lng]);
						count--;
						(function move (count) {
							var item = self.markers[id];

							item["timer"] = setTimeout(function () {
								count--;

								if (count > 0) {
									lat += dy;
									lng += dx;

									marker.setLatLng([lat, lng]);

									if (self.animationFollow == id) {
										self.map.map.setView([lat, lng], undefined, { animate: false });
									}

									move(count);
								} else {
									marker.setLatLng(latlng);

									if (self.animationFollow == id) {
										self.map.map.setView(latlng, undefined, { animate: false });
									}

									if (Util.isFunction(callback)) {
										callback();
									}
								}
							}, timeout);
						})(count);

						return marker;
					} else {
						this.eraseMarker(marker);

						if (Util.isFunction(callback)) {
							callback();
						}

						return null;
					}
				}
			},

			stopAnimation: function (id) {
				var self = this;

				if (id) {
					var item = this.markers[id];

					if (item) {
						var timer = item["timer"];

						if (timer) {
							clearTimeout(timer);
							item["timer"] = null;
						}

						var destination = item["destination"];

						if (destination && item.marker) {
							item.marker.setLatLng(destination);
						}

						item["destination"] = null;
					}
				} else {
					Util.each(this.markers, function (marker, id) {
						if (marker.timer) {
							self.stopAnimation(id);
						}
					});
				}
			}
		});
	}
);
