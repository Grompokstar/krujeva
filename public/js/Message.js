Module.define(
	"Events",

	function () {

	GLOBAL.Message = Static(Events, {
		sockjs: null,
		reconnect: true,
		reconnectTimeout: 1000,
		url: null,
		index: null,
		status: "closed",
		sessionId: null,
		waitTimeot: null,

		initialize: function () {
			this.ParentCall();

			setTimeout(function () {

				Events.on('No.Internet', this.onInternetOff, this);

			}.bind(this), 3000);
		},

		connect: function (options) {
			var self = this;

			console.log("Make connect");

			options = Util.object(options);

			this.reconnect = Util.coalesce(options.reconnect, this.reconnect, true);
			this.reconnectTimeout = Util.coalesce(options.reconnectTimeout, this.reconnectTimeout, 1000);

			this.sockjs = new SockJS(this.url);

			this.status = "connecting";
			this.emit("connecting");

			this.sockjs.onopen = function () {
				console.log("Message connected");

				self.authorize();

				self.status = "connected";
				self.emit("connected");
			};

			this.sockjs.onmessage = function (args) {

				switch (args.type) {
					case "message":
						var data;
						var index;

						try {
							data = JSON.parse(args.data);
						} catch (e) {
							console.log(e);

							data = null;
						}

						index = data.index;
						data = data.data;

						if (index) {
							self.index = index;
						}

						if (typeof(data) == "object" && data && data.event) {
							console.log(data.event, index, data.data);

							self.emit(data.event, data.data);
						} else {
							self.emit("data", data);
						}
						break;
					default:
						console.log("unknown type", args);
				}
			};

			this.sockjs.onclose = function () {
				console.log("Message closed");

				if (self.sockjs) {

					self.sockjs.close();
				}

				self.sockjs = null;

				if (self.reconnect) {

					if (self.waitTimeot) {
						clearTimeout(self.waitTimeot);
						self.waitTimeot = null;
					}

					self.waitTimeot = setTimeout(function () {
						self.connect();
					}, self.reconnectTimeout);
				}

				self.status = "closed";

				self.emit("closed");
			}
		},

		onInternetOff: function () {
			console.log("Message closed");

			if (this.sockjs) {

				this.sockjs.close();
			}

			this.sockjs = null;

			if (this.reconnect) {

				if (this.waitTimeot) {
					clearTimeout(this.waitTimeot);
					this.waitTimeot = null;
				}

				setTimeout(function () {

					this.connect();

				}.bind(this), this.reconnectTimeout);
			}

			this.status = "closed";

			this.emit("closed");
		},

		close: function () {
			this.reconnect = false;

			this.sockjs.close();
		},

		send: function (data) {
			this.sockjs.send(JSON.stringify(data));
		},

		authorize: function () {
			var data = {
				sessionId: this.sessionId
			};

			if (this.index) {
				data.index = this.index;
			}

			this.send({
				name: "authorize",
				data: data
			});
		}
	});
});
