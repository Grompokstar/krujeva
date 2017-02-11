Module.define(
	"Events",
	"Data.ServerSource",
	"Message",

	function () {
		NS("KrujevaDict.Data");

		KrujevaDict.Data.ServerSource = Class(Events, Data.ServerSource, {

			load: function (filter, callback, options) {

				filter = filter || {};

				options = options || {};

				var data = this.cachedData();

				var filterCRC = Util.crc32(JSON.stringify(filter));

				if (data.filter == filterCRC && data.items.length) {

					if (Util.isFunction(callback)) {
						callback(data.items);
					}

					return;
				}

				var source = new this.Class();

				var self = this;

				var loadMethod = options['loadMethod'] || 'page';

				source[loadMethod]({options: {unlimit: true, filter: filter}}, function (data) {

					if (data && data.items) {

						KrujevaDict.Data.ServerSource.setData(self.url, data.items, filter);

						if (Util.isFunction(callback)) {

							callback(data.items);
						}
					}
				});

				return this;
			},

			cachedData: function (id) {
				var data =  KrujevaDict.Data.ServerSource.getData(this.url);

				if (typeof id == 'undefined') {
					return data;
				}

				var items = data.items;

				var item = null;

				for (var i in items) if (items.hasOwnProperty(i)) {

					if (items[i]['id'] == id) {
						item = items[i];
						break;
					}
				}

				return item;
			}
		});

		KrujevaDict.Data.ServerSource.Static({

			subscribed: false,

			data: {},

			initialize: function () {

				if (!this.subscribed)  {

					this.subscribed = true;

					setTimeout(function () {

						var data = KrujevaDict.Data;

						for (var record in data) if (data.hasOwnProperty(record)) {

							this.listenDataEvent("KrujevaDict." + record + ".Insert", 'KrujevaDict.Data.' + record);
							this.listenDataEvent("KrujevaDict." + record + ".Update", 'KrujevaDict.Data.' + record);
							this.listenDataEvent("KrujevaDict." + record + ".Remove", 'KrujevaDict.Data.' + record);
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
