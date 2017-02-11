Module.define(function () {
	GLOBAL.Enum = new Static({
		eachExcludes: ["eachExcludes", "Parents"],

		name: function (value) {
			var name = null;

			this.each(function (val, item) {
				if (val == value) {
					name = item;
					return false;
				}

				return true;
			});

			return name;
		},

		title: function (value) {
			return this.name(value);
		},

		titles: function (values) {
			var self = this;
			var titles = [];

			Util.each(values, function (value) {
				titles.push(self.title(value));
			});

			return titles.join(", ");
		},

		values: function () {
			var values = [];

			this.each(function (value) {
				values.push(value);
			});

			return values;
		},

		each: function (callback) {
			for (var item in this) {
				if (this.hasOwnProperty(item)) {
					if (typeof(this[item]) != "function" && this.eachExcludes.indexOf(item) < 0) {
						if (callback.call(this, this[item], item) === false) {
							break;
						}
					}
				}
			}
		},

		order: function () {
			return [];
		},

		l: function (left, right) {
			var order = this.order();
			var idxLeft = order.indexOf(left);
			var idxRight = order.indexOf(right);

			return idxLeft > 0 && idxRight > 0 && idxLeft < idxRight;
		},

		le: function (left, right) {
			return left == right || this.l(left, right);
		},

		g: function (left, right) {
			var order = this.order();
			var idxLeft = order.indexOf(left);
			var idxRight = order.indexOf(right);

			return idxLeft > 0 && idxRight > 0 && idxLeft > idxRight;
		},

		ge: function (left, right) {
			return left == right || this.g(left, right);
		},

		next: function (value) {
			var order = this.order();
			var idx = order.indexOf(value);

			if (idx < 0 || idx >= order.length - 1) {
				return null;
			}

			return order[idx + 1];
		},

		prev: function (value) {
			var order = this.order();
			var idx = order.indexOf(value);

			if (idx < 1) {
				return null;
			}

			return order[idx - 1];
		}
	});
});
