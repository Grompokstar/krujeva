var ChildProcess = require("child_process");

GLOBAL.Master = Class({
	options: {},

	httpWorker: null,
	actionsWorker: null,
	eventsWorker: null,

	initialize: function (options) {
		this.options = options;
	},

	run: function () {
		this.runHttp();
		this.runActions();
		this.runEvents();
	},

	runHttp: function () {
		var self = this;

		this.httpWorker = ChildProcess.fork("./ThreadHttp", [this.options.port]);

		this.httpWorker.on("message", function (args) {
			self.onHttpMessage(args);
		});
	},

	runActions: function () {
		var self = this;

		this.actionsWorker = ChildProcess.fork("./ThreadActions", [this.options.amihost, this.options.amiport, this.options.amiuser, this.options.amisecret]);
	},

	runEvents: function () {
		var self = this;

		this.eventsWorker = ChildProcess.fork("./ThreadEvents", [this.options.amihost, this.options.amiport, this.options.amiuser, this.options.amisecret]);
	},

	sendAction: function (action, data) {
		this.actionsWorker.send({
			message: "action",
			action: action,
			data: data
		});
	},

	onHttpMessage: function (args) {
		switch (args.message) {
			case "action":
				switch (args.action) {
					case "System.Close":
						this.httpWorker.send({ message: "close" });
						this.actionsWorker.send({ message: "close" });
						this.eventsWorker.send({ message: "close" });
						break;
					default:
						this.sendAction(args.action, args.data);
						break;
				}

				break;
		}
	}
});
