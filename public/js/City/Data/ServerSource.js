Module.define(
	"Events",
	"Data.ServerSource",
	"Message",

	function () {
		NS("City.Data");

		City.Data.ServerSource = Class(Events, Data.ServerSource, {

			load: function (filter, callback) {

				filter = filter || {};

				var data = this.cachedData(this.url);

				var filterCRC = Util.crc32(JSON.stringify(filter));

				if (data.filter == filterCRC && data.items.length) {

					if (Util.isFunction(callback)) {
						callback(data.items);
					}

					return;
				}

				var source = new this.Class();

				var self = this;

				source.page({options: {unlimit: true, filter: filter}}, function (data) {

					if (data && data.items) {

						City.Data.ServerSource.setData(self.url, data.items, filter);

						if (Util.isFunction(callback)) {

							callback(data.items);
						}
					}
				});

				return this;
			},

			cachedData: function () {
				return City.Data.ServerSource.getData(this.url);
			}
		});

		City.Data.ServerSource.Static({

			subscribed: false,

			data: {},

			initialize: function () {

				if (!this.subscribed)  {

					this.subscribed = true;

					setTimeout(function () {

						var data = City.Data;

						for (var record in data) if (data.hasOwnProperty(record)) {

							this.listenDataEvent("City." + record + ".Insert", 'City.Data.' + record);
							this.listenDataEvent("City." + record + ".Update", 'City.Data.' + record);
							this.listenDataEvent("City." + record + ".Remove", 'City.Data.' + record);
						}

					}.bind(this), 0);
				}

			},

			listenDataEvent: function (messageEvent, sourceClass) {

				var self = this;

				Message.on(messageEvent, function (data) {

					var source = Util.create(sourceClass);

					self.clearData(source.url);

				}.bind(this));
			},

			getData: function (key) {

				if (typeof this.data[key] == 'undefined') {

					return {filter: Util.crc32(JSON.stringify({})), items: []};
				}

				return this.data[key];
			},

			clearData: function (key) {
				delete this.data[key];
			},

			setData: function (key, items, filter) {
				this.data[key] = {filter: Util.crc32(JSON.stringify(filter)), items: items};
			}
		});
	}
);
