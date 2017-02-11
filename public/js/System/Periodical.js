Module.define(

	function () {
		NS("System");

		System.Periodical = Class({
			callback: null,
			stopCallback: null,
			timeout: null,
			active: false,
			interval: null,

			initialize: function (callback, stopCallback) {
				this.callback = callback;
				this.stopCallback = stopCallback;
			},

			destroy: function () {
				this.stop();

				this.callback = null;

				return this;
			},

			start: function (interval) {
				this.interval = Util.coalesce(interval, 1000);

				if (!this.active) {
					this.active = true;

					this.exec();
				}

				return this;
			},

			stop: function () {
				if (this.timeout) {
					clearTimeout(this.timeout);
					this.timeout = null;
				}

				if (this.active) {
					this.active = false;

					if (Util.isFunction(this.stopCallback)) {
						this.stopCallback();
					}
				}

				return this;
			},

			exec: function () {
				var self = this;

				var callback = function () {
					self.timeout = setTimeout(function () {
						self.timeout = null;
						self.exec();
					}, self.interval);
				};

				try {
					this.callback(callback);
				} catch (e) {
					console.log("Periodical error", e);

					callback();
				}

				return this;
			}
		});
	}
);
