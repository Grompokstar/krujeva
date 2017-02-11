var Net = require("net");

GLOBAL.Socket = Class({
	socket: null,
	connected: false,

	initialize: function () {
		var self = this;

		this.socket = new Net.Socket();

		this.socket.setEncoding("utf8");

		this.socket.on("data", function (args) {
			self.onSocketData(args);
		});

		this.socket.on("connect", function (args) {
			self.onSocketConnect(args);
		});

		this.socket.on("error", function (args) {
			self.onSocketError(args);
		});

		this.socket.on("close", function (args) {
			self.onSocketClose(args);
		});
	},

	connect: function (options) {

	},

	send: function (command) {
		if (typeof(command) == "string") {
			command = [command];
		}

		if (!Util.isArray(command)) {
			return false;
		}

		command = command.join("\n");
		command += "\n\n";
	},

	readData: function (data) {
		var i;

		data = data.trim();

		var parts = data.split("\n\n");
		var header = parts[0].trim();
		var body = parts.length > 1 ? parts[1] : null;
		var headers = {};

		header = header.split("\n");

		for (i = 0; i < header.length; i++) {
			header[i] = header[i].split(/\s*:\s*/);

			if (header[i].length == 2) {
				headers[header[i][0]] = header[i][1];
			} else {
				console.log("Invalid header", header);
			}
		}

		return data;
	},

	onSocketData: function (data) {
		data = this.readData(data);
	},

	onSocketConnect: function () {
		this.connected = true;

		this.emit("connected");

		console.log("connected");
	},

	onSocketError: function (args) {
		console.log("socket error", args);
	},

	onSocketClose: function (error) {
		this.connected = false;

		this.emit("closed");

		console.log("socket closed", error ? "on error" : "");
	}
});
