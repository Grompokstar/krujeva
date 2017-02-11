Module.define("Data.Source", function () {
	NS("Data");

	Data.ServerSource = Class(Data.Source, {
		url: null,

		fields: [],
		names: {},

		initialize: function (url) {
			if (url) {
				this.url = url;
			}

			this.Parent();
		},

		get: function (options, callback) {
			return this.call("get", options, callback);
		},

		find: function (options, callback) {
			return this.call("find", options, callback);
		},

		page: function (options, callback) {
			return this.call("page", options, callback);
		},

		insert: function (item, callback) {
			return this.call("insert", { item: JSON.stringify(item) }, callback);
		},

		update: function (item, callback) {
			return this.call("update", { item: JSON.stringify(item) }, callback);
		},

		remove: function (options, callback) {
			return this.call("remove", options, callback);
		},

		call: function (name, options, callback) {
			if (callback) {
				return Xhr.call(this.url + name, options, function (response) {
					if (typeof(callback) == "function") {
						if (response.success) {
							callback(response.data, null);
						} else {
							callback(null, response);
						}
					}
				});
			} else {
				return Xhr.call(this.url + name, options);
			}
		}
	});
});
