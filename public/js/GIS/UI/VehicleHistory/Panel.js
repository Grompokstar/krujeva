Module.define(
	"UI.Widget.Base",
	"GIS.UI.VehicleHistory.Layer",
	"GIS.UI.VehicleHistory.Log",

	function () {
		NS("GIS.UI.VehicleHistory");

		GIS.UI.VehicleHistory.Panel = Class(UI.Widget.Base, {
			template: "GIS/VehicleHistory/Panel",
			popupTemplate: "GIS/VehicleHistory/Popup",

			historyLoadUrl: "Glonass/Web/VehicleHistory/Load",
			historyGetLastDateUrl: "Glonass/Web/VehicleHistory/GetLast",

			map: null,
			vehiclesSource: null,

			params: {
				vehicle: null,
				fromDateTime: null,
				toDateTime: null
			},

			historyLayer: null,
			historyLayerClass: "GIS.UI.VehicleHistory.Layer",
			xhr: null,
			log: null,

			speed: 1,
			vehicleSearch: null,
			history: [],
			historyLength: 0,
			currentIndex: 0,
			playingStatus: null,
			playTimeoutId: null,

			$logPanel: null,
			$rewind: null,
			$vehicle: null,
			$fromDateTime: null,
			$toDateTime: null,

			initialize: function (parent, options) {
				this.override(["map", "historyLoadUrl", "historyGetLastDateUrl", "vehiclesSource", "template", "popupTemplate", "params", "historyLayerClass"], options);

				this.playingStatus = "stop";
			},

			destroy: function () {
				this.historyLayer.destroy();
				this.historyLayer = null;

				this.ParentCall();
			},

			render: function () {
				var self = this;

				this.ParentCall();

//				this.use(UI.Plugin.Draggable, {
//					handle: this.$element
//				});

				this.$vehicle = this.$name("vehicle-selector");
				this.$fromDateTime = this.$name("from-date-time");
				this.$toDateTime = this.$name("to-date-time");
				this.$rewind = this.$name("rewind");

				this.historyLayer = Util.create(this.historyLayerClass, [this.map, { popupTemplate: this.popupTemplate }]);
				this.historyLayer.on("changePopupVisibility", this.onChangePopupVisibility, this);

				this.vehicleSearch = new UI.Widget.Text(this, {
					element: this.$vehicle
				}).use(UI.Plugin.DataAutocomplete, {
						source: this.vehiclesSource,
						columns: ["num", "modelname"],
						column: ["num"]
					}).on("select", function (args) {
						if (args.item) {
							self.reset();
							self.changeVehicle(args.item);
						}
					}).on("empty", function () {
						self.changeVehicle(null);
					});

				this.$(".datetime").datetimepicker().on("change", function () {
					self.reset();
				});

				this.$rewind.slider({
					min: this.currentIndex,
					start: function (event, ui) {
						if (self.playingStatus == "stop") {
							return;
						}

						self.pause();
					},
					stop: function (event, ui) {
						if (self.playingStatus == "stop") {
							return;
						}

						self.setHistoryPoint(ui.value);
						self.play();
					}
				}).hide();

				this.log = new GIS.UI.VehicleHistory.Log(this)
					.render().appendTo(this.$element)
					.on("destroy", function () {
						self.log = null;
					})
					.on("itemClick", this.onLogItemClick, this);


				this.refreshControls();
				this.refreshButtons();

				if (this.params.vehicle) {
					this.$name("set-last-signal-date").show();
				} else {
					this.$name("set-last-signal-date").hide();
				}

				return this;
			},

			align: function () {
				this.ParentCall();

				this.map.invalidate();
			},

			start: function () {
				this.reset();

				this.historyLayer.create(this.current());
				this.historyLayer.drawPaths(this.history);

				this.log.setData(this.history);

				this.renderStatistics();

				this.$rewind.slider("option", { max: this.historyLength });
				this.$rewind.show();

				this.play();
			},

			play: function () {
				var self = this;

				function move () {
					if (self.currentIndex < self.historyLength - 1) {
						if (self.playingStatus == "play") {
							self.currentIndex++;

							self.historyLayer.move(self.current(), move);

							self.log.highlight(self.currentIndex);

							self.$rewind.slider("option", { value: self.currentIndex });
						}
					} else {
						self.pause();
					}
				}

				this.playingStatus = "play";

				this.historyLayer.speedMultiplier = this.speed;

				move();

				this.refreshButtons();
			},

			pause: function () {
				this.playingStatus = "pause";

				clearTimeout(this.playTimeoutId);
				this.historyLayer.stopAnimation();

				this.refreshButtons();
			},

			reset: function () {
				this.playingStatus = "stop";

				this.currentIndex = 0;

				this.log.clear();

				this.historyLayer.stopAnimation();
				this.historyLayer.removeMarker();
				this.historyLayer.removePaths();

				this.$name("statistics-panel").empty();

				this.$rewind.slider("option", { value: this.currentIndex });

				this.refreshButtons();
			},

			renderStatistics: function () {
				var speed = 0;
				var mileage = 0;
				var motionCount = 0;

				for (var i = 0; i < this.historyLength; i++) {
					var item = this.history[i];

					mileage += parseFloat(item["run"]);

					if (item["speed"] > 0) {
						speed += parseFloat(item["speed"]);
						motionCount++;
					}
				}

				mileage = parseFloat(mileage / 1000).toFixed(2);

				if (motionCount > 0) {
					speed = parseFloat(speed / motionCount).toFixed(2);
				}

				this.$name("statistics-panel").html(
					UI.Template.render("GIS/VehicleHistory/Statistics", {
						begin: this.history[0].datetime,
						end: this.history[this.historyLength - 1].datetime,
						mileage: mileage,
						speed: speed
					})
				);
			},

			load: function () {
				var self = this;

				if (this.xhr) {
					this.xhr.abort();
				}

				this.history = null;

				var deferred = $.Deferred();

				this.xhr = Xhr.call(this.historyLoadUrl, {
					trackerId: this.params.vehicle.trackerid,
					fromDateTime: this.params.fromDateTime.format(),
					toDateTime: this.params.toDateTime.format()
				}, function (response) {
					if (response.success) {
						self.history = response.data.history;
						self.historyLength = self.history.length;

						deferred.resolve();
					} else {
						deferred.reject();
					}
				});

				return deferred.promise();
			},

			readControls: function () {
				this.params['fromDateTime'] = this.$fromDateTime.datetimepicker("getDate");
				this.params['toDateTime'] = this.$toDateTime.datetimepicker("getDate");
			},

			refreshControls: function () {
				if (this.params["vehicle"]) {
					this.$vehicle.val(this.params["vehicle"].num);
				}

				this.$fromDateTime.datetimepicker("setDate", this.params['fromDateTime']);
				this.$toDateTime.datetimepicker("setDate", this.params['toDateTime']);

				this.$("[name='speed'][value='" + this.speed + "']").prop("checked", true);
			},

			refreshButtons: function () {
				this.$name("buttons").html(
					UI.Template.render("GIS/VehicleHistory/Buttons", { widget: this })
				);
			},

			setHistoryPoint: function (index) {
				this.currentIndex = index;

				this.historyLayer.setHistoryPoint(this.current());

				this.$rewind.slider("value", this.currentIndex);

				this.log.highlight(this.currentIndex);
			},

			current: function () {
				return this.history[this.currentIndex];
			},

			incrementIndex: function (value) {
				var index = this.currentIndex + value;

				if (index < 0) {
					index = 0;
				}

				if (index > this.historyLength - 1) {
					index = this.historyLength - 1;
				}

				return index;
			},

			changeVehicle: function (vehicle) {
				this.params["vehicle"] = vehicle;

				if (vehicle) {
					this.$name("set-last-signal-date").show();
				} else {
					this.$name("set-last-signal-date").hide();
				}
				this.$name("set-last-signal-date").show();
			},

			onPlayClick: function (caller) {
				var self = this;

				this.readControls();

				if (!this.params["vehicle"]) {
					alert("Не выбрано ТС.");
					return;
				}

				if (!this.params["vehicle"].trackerid) {
					alert("Выбрано ТС без трекера.");
					return;
				}

				if (!this.params["fromDateTime"] || !this.params["toDateTime"]) {
					alert("Необходимо определить временной интервал.");
					return;
				}

				this.load().done(function () {
					self.start();
				}).fail(function () {
					alert("Нет данных за выбранный период.");
				});
			},

			onPauseClick: function (caller) {
				this.pause();
			},

			onResetClick: function (caller) {
				this.reset();
			},

			onContinueClick: function (caller) {
				this.play();
			},

			onLogItemClick: function (args) {
				var status = this.playingStatus;

				this.pause();
				this.setHistoryPoint(args.index);

				if (status == "play") {
					this.play();
				}
			},

			onRewindToStepClick: function (caller) {
				if (this.playingStatus == "stop") {
					return;
				}

				var sign = $(caller).data("direction") == "forward" ? 1 : -1;
				var step = Math.round(5 * (this.historyLength / 100));

				this.pause();
				this.setHistoryPoint(this.incrementIndex(sign * step))
				this.play();

				this.refreshButtons();
			},

			onTodayClick: function (caller) {
				var date = new Date();
				date.setHours(0, 0, 0);
				this.params["fromDateTime"] = date;

				var date = new Date();
				date.setHours(23, 59, 59);
				this.params["toDateTime"] = date;

				this.refreshControls();
			},

			onYesterdayClick: function (caller) {
				var date = new Date();
				date.setDate((new Date()).getDate() - 1);
				date.setHours(0, 0, 0);
				this.params["fromDateTime"] = date;

				var date = new Date();
				date.setDate((new Date()).getDate() - 1);
				date.setHours(23, 59, 59);
				this.params["toDateTime"] = date;

				this.refreshControls();
			},

			onSetLastSignalDateClick: function (caller) {
				var self = this;

				if (!this.params.vehicle.trackerid) {
					return false;
				}

				Xhr.call(this.historyGetLastDateUrl, {
					trackerId: this.params.vehicle.trackerid
				}, function (response) {
					if (response.success) {
						var toDateTime = Date.fromPG(response.data.last.datetime);

						var fromDateTime = new Date(toDateTime.valueOf())
						fromDateTime.setDate(fromDateTime.getDate() - 1);

						self.params["toDateTime"] = toDateTime;
						self.params["fromDateTime"] = fromDateTime;

						self.refreshControls();
					}
				});
			},

			onChangeSpeedClick: function (caller) {
				this.speed = $(caller).val();

				this.historyLayer.speedMultiplier = this.speed;
			},

			onTogglePathClick: function (caller) {
				var checked = $(caller).is(":checked");
				var name = $(caller).val();

				if (checked) {
					this.historyLayer.showPath(name);
				} else {
					this.historyLayer.hidePath(name);
				}
			},

			onToggleLogClick: function (caller) {
				var checked = $(caller).is(":checked");

				if (checked) {
					this.log.visible = true;
					this.log.show();
				} else {
					this.log.visible = false;
					this.log.hide();
				}
			},

			onTogglePopupClick: function (caller) {
				var checked = $(caller).is(":checked");
				this.historyLayer.setPopupVisibility(checked);
			},

			onChangePopupVisibility: function (args) {
				this.$name("popup-toggler").prop("checked", args.visible);
			},

			onHistoryFollowClick: function (caller) {
				this.historyLayer.animationFollow = $(caller).is(":checked");
			},

			onCloseClick: function (caller) {
				this.reset();

				this.hide();
				this.emit("close");
				return false;
			}
		});
	}
);
