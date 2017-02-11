Module.define(
	function () {
		GLOBAL.ServerTime = Static({
			interval: null,
			diff: 0,
			localediff: 0,

			sync: function () {
				var self = this;

				Xhr.request("Index/timestamp", function (timestamp, error, jqXHR) {

					if (jqXHR && jqXHR['errorCode'] == 106) {
						Events.emit('No.Internet');
					}

					if (!error) {
						self.setTimestamp(timestamp);
					} else {
						console.log("ServerTime error");
					}
				});
			},

			setTimestamp: function (timestamp) {

				var local = Date.now();

				this.diff = local - (+timestamp) * 1000;
			},

			setTimeZone: function (timezone) {
				this.localediff = (+timezone)*60*60* 1000;
			},

			start: function (period) {
				var self = this;

				period = Util.coalesce(period, 30000);

				if (!this.interval) {
					this.sync();

					this.interval = setInterval(function () {
						self.sync();
					}, period);
				}
			},

			stop: function () {
				if (this.interval) {
					clearInterval(this.interval);

					this.interval = null;
				}
			},

			now: function () {
				return Date.now() - this.diff + this.localediff;
			},

			date: function () {
				return new Date(this.now());
			}
		});
	}
);
