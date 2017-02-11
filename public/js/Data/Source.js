Module.define(function () {
	NS("Data");

	Data.Source = Class({
		fields: [],
		fieldNames: {},

		initialize: function () {
			for (var i = 0; i < this.fields.length; i++) {
				var field = this.fields[i];

				this.fieldNames[field.name] = field;
			}
		},

		create: function () {
			var item = {};

			for (var i = 0; i < this.fields.length; i++) {
				item[this.fields[i].name] = null;
			}

			return item;
		},

		get: function (options, callback) {
			return this.result(callback, null, true);
		},

		find: function (options, callback) {
			return this.result(callback, [], true);
		},

		page: function (options, callback) {
			return this.result(callback, {
				count: 0,
				items: []
			}, true);
		},

		insert: function (item, callback) {
			return this.result(callback, false, true);
		},

		update: function (item, callback) {
			return this.result(callback, false, true);
		},

		remove: function (options, callback) {
			return this.result(callback, false, true);
		},

		result: function (callback, data, error) {
			if (typeof(callback) == "function") {
				callback(data, error);
			}

			return data;
		}
	});
});
