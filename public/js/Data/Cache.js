Module.define(
	function () {
		NS("Data");

		Data.Cache = Static({
			values: {},

			cached: function (callback, key, timeout) {
				var self = this;

				timeout = Util.coalesce(timeout, 3600);

				if (this.values[key] === undefined) {
					this.values[key] = callback();

					setTimeout(function () {
						delete self.values[key];
					}, timeout);
				}

				return this.values[key];
			}
		});
	}
);
