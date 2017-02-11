Module.define(
	function () {
		NS("Data");

		Data.Item = Class({});

		Data.Item.Static({
			diff: function (item1, item2, options) {
				item1 = Util.object(item1);
				item2 = Util.object(item2);
				options = Util.object(options);

				var diff = [];
				var exclude = Util.array(options["exclude"]);
				var fields = Util.array(options["fields"]);

				Util.each(item2, function (value, name) {
					if (fields.length) {
						if (!~fields.indexOf(name)) {
							return;
						}
					} else if (~exclude.indexOf(name)) {
						return;
					}

					if (!Util.equal(item1[name], item2[name])) {
						diff.push(name);
					}
				});

				if (options.object) {
					var object = {};

					for (var i = 0; i < diff.length; i++) {
						var field = diff[i];

						object[field] = item2[field];
					}

					diff = object;
				}

				if (options.clone) {
					diff = Util.clone(diff);
				}

				return diff;
			}
		});
	}
);
