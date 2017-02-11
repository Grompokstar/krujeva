var Net = require("net");

GLOBAL.AMI = Class(Events, {
	socket: null,
	connected: false,
	greetingRegExp: null,
	reconnectTimeout: 1000,

	actionCallbacks: {},
	actionsQueue: [],

	actionIDPrefix: "AMI_",
	actionIDCounter: 0,

	options: {},

	shutdown: false,

	eventsMode: null,
	logEvents: false,

	initialize: function (options) {
		var self = this;

		this.options = options;

		if (options.prefix) {
			this.actionIDPrefix = options.prefix;
		}

		this.socket = new Net.Socket();

		this.socket.setEncoding("utf8");

		this.socket.on("data", function (data) {
			if (data.match(self.greetingRegExp)) {
				data = data.replace(self.greetingRegExp, "");
			}

			self.readData(data);
		});

		this.socket.on("connect", function () {
			self.connected = true;

			self.emit("connected");
		});

		this.socket.on("error", function (error) {
			console.log("socket error", error);
		});

		this.socket.on("close", function () {
			self.connected = false;

			self.emit("closed");
		});

		this.greetingRegExp = new RegExp("^Asterisk[^\r\n]*\r\n");

		this.connect().on("connected", function () {
			self.login(self.options.amiuser, self.options.amisecret, function (response) {
				if (response != "Success") {
					self.close();

					self.emit("loginFailed");
				} else {
					if (self.eventsMode) {
						self.events(self.eventsMode);
					}

					self.emptyActionsQueue();
				}
			});
		}).on("closed", function () {
			if (!self.shutdown) {
				console.log("connection closed, reconnecting...");
				self.reconnect();
			}
		});
	},

	connect: function () {
		this.socket.connect(this.options.port, this.options.host);

		return this;
	},

	reconnect: function () {
		var self = this;

		setTimeout(function () {
			self.connect();
		}, this.reconnectTimeout);
	},

	close: function () {
		this.shutdown = true;

		this.socket.destroy();

		return this;
	},

	login: function (username, secret, callback) {
		return this.action("Login", {
			Username: username,
			Secret: secret
		}, callback);
	},

	logout: function (callback) {
		return this.action("Logoff", null, callback);
	},

	events: function (mode, callback) {
		return this.action("Events", {
			EventMask: mode
		}, callback);
	},

	action: function (name, args, callback) {
		var self = this;
		var command = "";
		var actionId = this.actionIDPrefix + (++this.actionIDCounter);

		console.log("action", name, "actionId", actionId);

		command = this.appendCommand(command, "Action", name);
		command = this.appendCommand(command, "ActionID", actionId);

		if (args) {
			Util.each(args, function (value, key) {
				command = self.appendCommand(command, key, value);
			});
		}

		command = this.finishCommand(command);

		if (this.connected) {
			this.doAction(actionId, command, callback);
		} else {
			this.actionsQueue.push({
				actionId: actionId,
				command: command,
				callback: callback
			});
		}

		return actionId;
	},

	emptyActionsQueue: function () {
		while (this.actionsQueue.length) {
			var item = this.actionsQueue.shift();

			this.doAction(item.actionId, item.command, item.callback);
		}
	},

	doAction: function (actionId, command, callback) {
		this.socket.write(command);

		if (Util.isFunction(callback)) {
			this.actionCallbacks[actionId] = callback;
		}
	},

	appendCommand: function (command, key, value, last) {
		command += key + ": " + value + "\r\n";

		if (last) {
			command += "\r\n";
		}

		return command;
	},

	finishCommand: function (command) {
		return command + "\r\n";
	},

	readData: function (data) {
		var self = this;
		var packets = data.split("\r\n\r\n");

		Util.each(packets, function (packet) {
			packet = packet.trim();
			if (packet) {
				self.readPacket(packet);
			}
		});
	},

	readPacket: function (packet) {
		var lines = packet.split("\r\n");
		var packetType;
		var packetName;
		var packetData = {};

		for (var i = 0; i < lines.length; i++) {
			var line = lines[i];
			var items = line.split(":");
			var key = items.shift().trim();
			var value = items.join(":").trim();

			if (i == 0) {
				packetType = key;
				packetName = value;
			} else {
				packetData[key] = value;
			}
		}

		switch (packetType) {
			case "Response":
				var actionId = packetData["ActionID"];

				if (actionId) {
					var callback = this.actionCallbacks[actionId];

					if (Util.isFunction(callback)) {
						callback(packetName, packetData);
					}
				}

				break;
			case "Event":
				var eventName = "AMI." + packetName;

				if (this.logEvents) {
					if (packetName != "VarSet") {
						console.log(eventName, packetData);
					}
				}

				this.emit(eventName, packetData);

				break;
		}
	}
});
