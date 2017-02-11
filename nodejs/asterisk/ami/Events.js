GLOBAL.Events = Class({
	eventsSubscriptions: {},

	on: function (event, callback, context) {
		if (!this.eventsSubscriptions[event]) {
			this.eventsSubscriptions[event] = [];
		}

		this.eventsSubscriptions[event].push({
			callback: callback,
			context: context,
			type: "on"
		});

		return this;
	},

	once: function (event, callback, context) {
		if (!this.eventsSubscriptions[event]) {
			this.eventsSubscriptions[event] = [];
		}

		this.eventsSubscriptions[event].push({
			callback: callback,
			context: context,
			type: "once"
		});

		return this;
	},

	off: function (event, callback, context) {
		if (!event) {
			for (event in this.eventsSubscriptions) {
				if (this.eventsSubscriptions.hasOwnProperty(event)) {
					this.off(event);
				}
			}
		} else {
			var subscriptions = this.eventsSubscriptions[event];

			if (subscriptions) {
				for (var i = 0, count = subscriptions.length; i < count; i++) {
					if ((subscriptions[i].callback == callback || !callback) && (subscriptions[i].context == context || !context)) {
						subscriptions.splice(i, 1);
						break;
					}
				}
			}
		}

		return this;
	},

	emit: function (event, args) {
		var subscriptions = this.eventsSubscriptions[event];

		if (subscriptions) {
			var i, count;
			var callbacks = [];

			for (i = 0, count = subscriptions.length; i < count; i++) {
				callbacks.push(subscriptions[i]);

				if (subscriptions[i].type == "once") {
					subscriptions.splice(i, 1);
					i--;
					count--;
				}
			}

			for (i = 0, count = callbacks.length; i < count; i++) {
				try {
					callbacks[i].callback.call(callbacks[i].context ? callbacks[i].context : this, args, this);
				} catch (e) {
					console.log("Events error", e);
					console.trace();
				}
			}
		}

		return this;
	}
});

GLOBAL.Events.Static({
	initialize: function () {
		this.eventsSubscriptions = {};
	},

	on: function (event, callback, context) {
		if (!this.eventsSubscriptions[event]) {
			this.eventsSubscriptions[event] = [];
		}

		this.eventsSubscriptions[event].push({
			callback: callback,
			context: context,
			type: "on"
		});

		return this;
	},

	once: function (event, callback, context) {
		if (!this.eventsSubscriptions[event]) {
			this.eventsSubscriptions[event] = [];
		}

		this.eventsSubscriptions[event].push({
			callback: callback,
			context: context,
			type: "once"
		});

		return this;
	},

	off: function (event, callback, context) {
		if (!event) {
			for (event in this.eventsSubscriptions) {
				if (this.eventsSubscriptions.hasOwnProperty(event)) {
					this.off(event);
				}
			}
		} else {
			var subscriptions = this.eventsSubscriptions[event];

			if (subscriptions) {
				for (var i = 0, count = subscriptions.length; i < count; i++) {
					if ((subscriptions[i].callback == callback || !callback) && (subscriptions[i].context == context || !context)) {
						subscriptions.splice(i, 1);
						break;
					}
				}
			}
		}

		return this;
	},

	emit: function (event, args) {
		var subscriptions = this.eventsSubscriptions[event];

		if (subscriptions) {
			var i, count;
			var callbacks = [];

			for (i = 0, count = subscriptions.length; i < count; i++) {
				callbacks.push(subscriptions[i]);

				if (subscriptions[i].type == "once") {
					subscriptions.splice(i, 1);
					i--;
					count--;
				}
			}

			for (i = 0, count = callbacks.length; i < count; i++) {
				try {
					callbacks[i].callback.call(callbacks[i].context ? callbacks[i].context : this, args, this);
				} catch (e) {
					console.log("Events error", e);
					console.trace();
				}
			}
		}

		return this;
	}
});
