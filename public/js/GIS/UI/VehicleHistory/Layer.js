Module.define(
	"GIS.UI.Layer.Base",

	function () {
		NS("GIS.UI.VehicleHistory");

		GIS.UI.VehicleHistory.Layer = Class(GIS.UI.Layer.Base, {
			markerGroup: null,
			paths: null,

			item: null,

			marker: null,

			timer: null,
			destination: null,
			speedMultiplier: 1,

			popupTemplate: null,
			popupVisible: false,

			animationTimeout: 0,
			animationFrames: null,
			animationFollow: false,

			icons: {},

			initialize: function (map, options) {
				this.ParentCall();

				this.override(["popupTemplate"], options);

				this.initPaths();

				this.markerGroup = (new L.FeatureGroup());
				this.map.map.addLayer(this.markerGroup);
			},

			initPaths: function () {
				this.paths = null;

				this.paths = {
					signalPoints: {
						group: new L.FeatureGroup(),
						visible: false,
						style: { color: "green", fill: true, opacity: 1 }
					},
					normal: {
						group: new L.FeatureGroup(),
						visible: true,
						style: { color: "blue", weight: 7, opacity: 1 }
					},
					megaSpeed: {
						group: new L.FeatureGroup(),
						visible: true,
						style: { color: "red", weight: 7, opacity: 1 },
						checker: function (first, second) {
							return second.speed > 180;
						}
					},
					jump: {
						group: new L.FeatureGroup(),
						visible: true,
						style: { color: "orange", weight: 7, opacity: 1 },
						checker: function (first, second) {
							return second.duration > 0 && (second.run / second.duration) > 50;
						}
					},
					noSignals: {
						group: new L.FeatureGroup(),
						visible: true,
						style: { color: "yellow", weight: 7, opacity: 1},
						checker: function (first, second) {
							return Date.fromTZ(second.datetime).getTime() - Date.fromTZ(first.datetime).getTime() > 60000;
						}
					}
				};
			},

			destroy: function () {
				this.paths = null;

				this.ParentCall();
			},

			create: function (data) {
				var self = this;

				var latlng = GIS.Leaflet.fromGeog(data.geog);

				this.item = {
					latlng: latlng,
					geog: data.geog,
					attributes: data
				};

				this.marker = L.marker(latlng, this.getStyle(data)).bindPopup(
					UI.Template.render(this.popupTemplate, {log: this.item.attributes}),
					{closeButton: false, keepInView: false, autoPan: false}
				).on("popupopen", function () {
						self.popupVisible = true;
						self.emit("changePopupVisibility", { visible: self.popupVisible });
					}).on("popupclose", function () {
						self.popupVisible = false;
						self.emit("changePopupVisibility", { visible: self.popupVisible });
					});

				this.markerGroup.addLayer(this.marker);
			},

			removeMarker: function () {
				this.markerGroup.removeLayer(this.marker);
				this.marker = null;
			},

			update: function (data) {
				this.setStyle(this.getStyle(data));

				this.item.attributes = data;
			},

			oldLatLng: null,
			nextLatLng: null,

			move: function (data, callback) {

				var latlng = GIS.Leaflet.fromGeog(data.geog);

				this.nextLatLng = latlng;

				this.update(data);

				if (this.popupVisible) {
					this.marker.setPopupContent(UI.Template.render(this.popupTemplate, {log: this.item.attributes }));

					if (!this.marker._map.hasLayer(this.marker._popup)) {
						this.marker.openPopup();
					}
				}

				this.calculateAndSetSpeed(this.item.attributes["speed"]);

				this.moveMarker(latlng, callback);

				this.item.latlng = latlng;
				this.item.geog = data.geog;
			},

			moveMarker: function (latlng, callback) {
				var self = this;
				var fromLatLng = this.marker.getLatLng();

				this.oldLatLng = fromLatLng;

				this.destination = latlng;

				if (fromLatLng.equals(latlng)) {
					if (Util.isFunction(callback)) {
						callback();
					}

					return;
				}

				var count = this.animationFrames;
				var timeout = this.animationTimeout;

				var dx = (latlng.lng - fromLatLng.lng) / (count + 1);
				var dy = (latlng.lat - fromLatLng.lat) / (count + 1);
				var lat = fromLatLng.lat + dy;
				var lng = fromLatLng.lng + dx;

				if (count == 1) {
					if (self.animationFollow) {
						this.map.map.setView(latlng, undefined, { animate: false });
					}

					this.marker.setLatLng(latlng);

					if (Util.isFunction(callback)) {
						callback();
					}
				} else {
					if (self.animationFollow) {
						self.map.map.setView([lat, lng], undefined, { animate: false });
					}

					this.marker.setLatLng([lat, lng]);
					count--;

					(function move (count) {
						self.timer = setTimeout(function () {
							if (count > 0) {
								lat += dy;
								lng += dx;

								if (self.animationFollow) {
									self.map.map.setView([lat, lng], undefined, { animate: false });
								}

								self.marker.setLatLng([lat, lng]);

								move(count - 1);
							} else {
								if (self.animationFollow) {
									self.map.map.setView(latlng, undefined, { animate: false });
								}

								self.marker.setLatLng(latlng);

								if (Util.isFunction(callback)) {
									callback();
								}
							}
						}, timeout);
					})(count);
				}
			},

			calculateAndSetSpeed: function (speed) {

				if (this.oldLatLng && this.nextLatLng) {

					var distance = Math.ceil(this.oldLatLng.distanceTo(this.nextLatLng));

					this.animationFrames = Math.max(distance * 0.15 - (speed / 4), 20);

				} else {

					this.animationFrames = 200;
				}


			},

			setHistoryPoint: function (data) {
				this.update(data);

				this.map.setCenter(data.geog);

				var latlng = GIS.Leaflet.fromGeog(data.geog);

				if (!this.marker.getLatLng().equals(latlng)) {
					this.marker.setLatLng(latlng);
				}

				if (this.popupVisible) {
					this.marker.setPopupContent(UI.Template.render(this.popupTemplate, {log: this.item.attributes }));
				}
			},

			drawPaths: function (history) {
				var self = this;
				var segments = {}, points = [],
					first, second,
					firstLatLng, secondLatLng,
					normal;

				Util.each(this.paths, function (path, name) {
					segments[name] = [];
				});

				for (var i = 0, count = history.length - 1; i < count; i++) {
					first = history[i];
					second = history[i + 1];

					firstLatLng = GIS.Leaflet.fromGeog(first.geog);
					secondLatLng = GIS.Leaflet.fromGeog(second.geog);

					segments["signalPoints"].push(firstLatLng);

					normal = true;

					Util.each(this.paths, function (path, name) {
						if (path.checker && path.checker(first, second)) {
							segments[name].push([firstLatLng, secondLatLng]);
							normal = false;
						}
					});

					if (normal) {
						segments["normal"].push([firstLatLng, secondLatLng]);
					}
				}

				segments["signalPoints"].push(secondLatLng);

				Util.each(this.paths, function (path, name) {
					if (name != "signalPoints" && segments[name].length > 0) {
						path["group"].addLayer(new L.MultiPolyline(segments[name], path["style"]));
					}
				});

				var signalPoints = this.paths["signalPoints"];
				Util.each(segments["signalPoints"], function (point) {
					signalPoints["group"].addLayer(new L.Circle(point, 1, signalPoints["style"]));
				});

				this.map.map.fitBounds(signalPoints["group"].getBounds());

				Util.each(this.paths, function (path) {
					if (path["visible"]) {
						self.map.map.addLayer(path["group"]);
					}
				});
			},

			removePaths: function () {
				var self = this;

				Util.each(this.paths, function (path) {
					self.map.map.removeLayer(path["group"]);
				});
			},

			showPath: function (name) {
				var path = this.paths[name];
				path["visible"] = true;

				if (!this.map.map.hasLayer(path["group"])) {
					this.map.map.addLayer(path["group"]);
				}
			},

			hidePath: function (name) {
				var path = this.paths[name];
				path["visible"] = false;

				if (this.map.map.hasLayer(path["group"])) {
					this.map.map.removeLayer(path["group"]);
				}
			},

			setPopupVisibility: function (value) {
				this.popupVisible = value;

				if (this.marker) {
					if (this.popupVisible) {
						this.marker.openPopup();
					} else {
						this.marker.closePopup();
					}
				}
			},

			stopAnimation: function () {
				if (this.timer) {
					clearTimeout(this.timer);
					this.timer = null;
				}

				if (this.destination) {
					if (this.animationFollow) {
						this.map.map.setView(this.destination, undefined, { animate: false });
					}

					this.marker.setLatLng(this.destination);

					this.destination = null;
				}
			},

			setStyle: function (style) {
				if (style.icon) {
					this.marker.setIcon(style.icon);
				}
			},

			getStyle: function (data) {
				var style = {
					iconUrl: this.getVehicleIconUrl(data),
					iconSize: [35, 35]
				};

				return {
					icon: this.getIcon(style)
				};
			},

			getIcon: function (style) {
				if (!this.icons[style.iconUrl]) {
					this.icons[style.iconUrl] = this.createIcon(style);
				}

				return this.icons[style.iconUrl];
			},

			createIcon: function (style) {
				var options = {};

				if (style.iconUrl) {
					options.iconUrl = style.iconUrl;
				}

				if (style.iconSize) {
					options.iconSize = style.iconSize;
				}

				return L.icon(options);
			},

			getVehicleIconUrl: function (data) {
				var folderName = "unk";
				var img = this.courseImage(data.course, data.speed);
				var ext = ".gif";

				return "/images/emergency/icons/vehicles/" + folderName + "/" + img + ext;
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
