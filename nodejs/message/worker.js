var Cluster = require("cluster");
var SockJS = require("sockjs");
var Http = require("http");
var Memcached = require("memcached");
var Redis = require("redis");

function Worker(options) {
	var connections = {};
	var registered = {};

	var http = null;
	var sockjs = null;
	var memcached = null;
	var redis = null;
	var storage = options.storage;

	storage.type = storage.type ? storage.type : "memcached";
	storage.tag = storage.tag ? storage.tag : "";

	function init() {
		http = Http.createServer();
		sockjs = SockJS.createServer();

		switch (storage.type) {
			case "redis":
				if (!storage.host) storage.host = "127.0.0.1";
				if (!storage.port) storage.port = 6379;

				redis = Redis.createClient(storage.port, storage.host);
				break;
			case "memcached":
				if (!storage.host) storage.host = "127.0.0.1";
				if (!storage.port) storage.port = 11211;

				memcached = new Memcached(storage.host + ":" + storage.port);
				break;
		}

		sockjs.on("connection", function (connection) {
			console.log("connected", connection.remoteAddress + ":" + connection.remotePort, connection.id);

			connections[connection.id] = connection;

			connection.on("data", function (data) {
				onConnectionData(connection, data);
			});

			connection.on("close", function () {
				onConnectionClose(connection);
			})
		});

		var listen = options.listen ? options.listen : "0.0.0.0";
		var port = options.port ? options.port : 10001;
		var prefix = options.prefix ? options.prefix : "/sockjs";

		sockjs.installHandlers(http, { prefix: prefix });

		http.listen(port, listen, function () {
			console.log("worker", Cluster.worker.id, "listening on", listen + ":" + port);
		});

		http.on("connection", function () {
			console.log("worker", Cluster.worker.id);
		});

		process.on("message", function (message) {
			switch (message.name) {
				case "message":
					onMasterMessage(message.data);
					break;
				case "eventindex":
					onMasterEventIndex(message.data);
					break;
				case "history":
					onMasterHistory(message.data);
					break;
				case "clients":
					onMasterClients(message.data);
					break;
			}
		});
	}

	function cacheGet(key, callback) {
		switch (storage.type) {
			case "redis":
				redis.get(key, function (error, result) {
					callback(error, result);
				});
				break;
			case "memcached":
				memcached.get(key, function (error, result) {
					callback(error, result);
				});
				break;
		}
	}

	function authorizeConnection(connection, sessionId, callback) {
		function authLog() {
			var args = [].splice.call(arguments, 0).concat([connection.remoteAddress + ":" + connection.remotePort, connection.id, sessionId]);

			args.unshift("authorization:");

			console.log.apply(console, args);
		}

		authLog("authorizing");

		var key = sessionId + storage.tag;

		console.log("reading session context", key);

		cacheGet(key, function (error, result) {
			if (error) {
				authLog("cache error", error);

				return closeConnection(connection.id);
			}

			var context = null;

			try {
				context = JSON.parse(result);
			} catch (e) {
				context = null;
			}

			if (!context) {
				authLog("context failed", result);

				return closeConnection(connection.id);
			}

			registered[connection.id] = {
				context: context,
				connection: connection
			};

			authLog("authorized");

			if (typeof(callback) == "function") {
				callback();
			}

			return true;
		});
	}

	function closeConnection(id) {
		console.log("close", id);
		connections[id].close();

		delete registered[id];

		return true;
	}

	function send(connection, object) {
		connection.write(JSON.stringify(object));
	}

	function sendMessage(registeredConnection, data, callback) {
		try {
			if (!callback || callback(registeredConnection.context, data)) {
				send(registeredConnection.connection, data);
			}
		} catch (e) {
			console.log(e);
		}
	}

	function onMasterMessage(data) {
		var callback = null;
		var failed = false;

		if (data.callback && data.callback.length) {
			try {
				eval("callback = " + data.callback + ";");
			} catch (e) {
				failed = true;
			}
		}

		if (failed) {
			console.log("callback eval failed", data.callback);
			return false;
		}

		for (var id in registered) {
			if (registered.hasOwnProperty(id)) {
				var connection = registered[id];

				sendMessage(connection, { index: data.index, data: data.data }, callback);
			}
		}

		return true;
	}

	function onMasterEventIndex(data) {
		var connection = registered[data.id];
		var index = data.index;

		if (!connection) {
			return;
		}

		sendMessage(connection, {
			data: {
				event: "authorized",
				index: index
			}
		}, null);

		if (data.items.length) {
			onMasterHistory(data);
		}
	}

	function onMasterHistory(data) {
		var connection = registered[data.id];
		var items = data.items;
		var callback = null;

		if (connection) {
			for (var i = 0, count = items.length; i < count; i++) {
				var item = items[i];
				var failed = false;

				if (item.callback && item.callback.length) {
					try {
						eval("callback = " + item.callback + ";");
					} catch (e) {
						failed = true;
					}
				}

				if (!failed) {
					sendMessage(connection, { index: item.index, data: item.data }, callback);
				}
			}
		}
	}

	function onMasterClients(index) {
		var contexts = [];

		for (var i in registered) if (registered.hasOwnProperty(i)) {
			contexts.push(registered[i].context);
		}

		process.send({
			name: "clients", data: {
				contexts: contexts, index: index
			}
		});
	}

	function onConnectionData(connection, data) {
		console.log("got", data);
		if (data) {
			try {
				data = JSON.parse(data);
			} catch (e) {
				console.log(e);

				data = null;
			}
		}

		if (!data || typeof(data) != "object") {
			return closeConnection(connection.id);
		}

		var name = data.name;
		data = data.data;

		if (!data || typeof(data) != "object") {
			return closeConnection(connection.id);
		}

		switch (name) {
			case "authorize":
				var sessionId = data.sessionId;
				var index = +data.index;

				//@cookie sid
				if (connection.headers && connection.headers['x-forwarded-proto']) {
					sessionId = connection.headers['x-forwarded-proto'];
				}

				if (!sessionId) {
					return closeConnection(connection.id);
				}

				authorizeConnection(connection, sessionId, function () {

					process.send({
						name: "eventindex",
						data: {
							id: connection.id,
							index: (!isNaN(index) && index) ? index: null
						}
					});

				});

				break;
		}

		return true;
	}

	function onConnectionClose(connection) {
		console.log("closed", connection.remoteAddress + ":" + connection.remotePort, connection.id);

		delete connections[connection.id];
		delete registered[connection.id];
	}

	var AccessMode = {
		Read: 1,
		Insert: 2,
		Update: 3,
		Remove: 4,
		Execute: 5
	};

	function check(context, key, mode) {
		if (!context || !context.role) {
			return false;
		}

		if (context.role.name == "root") {
			return true;
		}

		if (mode === undefined) {
			mode = AccessMode.Execute;
		}

		var granted = context.role.access[key];

		if (!granted) {
			granted = [];
		}

		return !!~granted.indexOf(mode);
	}

	init();
}

module.exports = Worker;
