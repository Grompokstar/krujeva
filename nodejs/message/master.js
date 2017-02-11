var HttpServer = require("./http-server");
var Redis = require("redis");

function Master(options) {
	function eachWorker(callback) {
		for (var id in Cluster.workers) {
			if (Cluster.workers.hasOwnProperty(id)) {
				callback(Cluster.workers[id]);
			}
		}
	}

	var workers = 1;

	var queueClients = {};

	for (var i = 0; i < workers; i++) {
		var worker = Cluster.fork();

		(function (worker) {
			worker.on("message", function (message) {
				switch (message.name) {
					case "eventindex":
						var connectionId = message.data.id;
						var connectionIndex = message.data.index;
						var items = connectionIndex ? queue.findFrom(connectionIndex) : [];

						worker.send({
							name: "eventindex",
							data: {
								id: connectionId,
								index: queue.getLastIndex(),
								items: items
							}
						});
						break;

					case "history":
						var index = message.data.index;
						var connectionId = message.data.id;
						var items = queue.findFrom(index);

						worker.send({
							name: "history",
							data: {
								id: connectionId,
								items: items
							}
						});
						break;

					case "clients":
						var index = message.data.index;
						var contexts = message.data.contexts;

						if (queueClients[index]) {

							for (var i in contexts) if (contexts.hasOwnProperty(i)) {
								queueClients[index]['contexts'].push(contexts[i]);
							}

							queueClients[index]['count']--;

							if (!queueClients[index]['count']) {

								queueClients[index]['response'].end(JSON.stringify(queueClients[index]['contexts']));

								delete queueClients[index];
							}
						}
						break;
				}
			});
		})(worker);
	}

	var queueSize = options.queueSize ? options.queueSize : 100;

	var queue = new Queue(queueSize);

	listenHttp();

	switch (options.mode) {
		case "redis":
			listenRedis();
			break;
	}

	function listenHttp() {
		var http = new HttpServer({
			host: options.listen ? options.listen : "0.0.0.0",
			port: options.port ? options.port : 10002
		});

		http.on("request", function (args) {
			var response = args.response;
			var data = args.data;

			try {
				if (data.index) {
					data.index = +data.index;
				}

				if (data.data) {
					data.data = JSON.parse(data.data);
				}
			} catch (e) {
				console.log(e);
			}

			if (args.path == "/message" || !data || typeof(data) != "object") {
				var index = +data.index;
				var callback = data.callback;

				var messageData = {
					data: data.data,
					callback: callback
				};

				if (!isNaN(index)) {
					messageData.index = index;
					queue.push(messageData);
				}

				var message = {
					name: "message",
					data: messageData
				};

				eachWorker(function (worker) {
					worker.send(message);
				});

				response.end("OK");
			} else if (args.path == "/clients" || !data || typeof(data) != "object") {
				var index = +data.index;

				queueClients[index] = {
					contexts: [], count: workers, response: response
				};

				var message = {
					name: "clients", data: data.index
				};

				eachWorker(function (worker) {
					worker.send(message);
				});

			} else {
				response.end("Not found");
			}

		});

		http.listen();
	}

	function listenRedis() {
		var storage = options.storage;

		if (!storage.host) storage.host = "127.0.0.1";
		if (!storage.port) storage.port = 6379;

		var redis = Redis.createClient(storage.port, storage.host);
		var channel = options.channel ? options.channel : "ASYNC.MESSAGE";

		redis.subscribe(channel, function (error, channel) {
			if (error) {
				throw "failed to subscribe channel";
			} else {

				redis.on("message", function (channel, msg) {
					try {
						var data = JSON.parse(msg);

						var index = +data.index;
						var callback = data.callback;

						var messageData = {
							data: data.data,
							callback: callback
						};

						if (!isNaN(index)) {
							messageData.index = index;
							queue.push(messageData);
						}

						var message = {
							name: "message",
							data: messageData
						};

						eachWorker(function (worker) {
							worker.send(message);
						});
					} catch (e) {
						console.log("failed to parse message", msg, e);
					}
				});

				redis.on("end", function () {
					setTimeout(function () {
						console.log("REDIS: connection end");
						console.log("REDIS: reconnect");
						listenRedis();
					}, 1000);
				});
			}
		});
	}
}

function Queue(size) {

	var items = [];
	var currentSize = 0;

	this.push = function (item) {
		items.push(item);

		if (currentSize == size) {
			items.shift();
		} else {
			currentSize++;
		}
	};

	this.getLastIndex = function () {
		var lastItem = items.slice(-1);
		return typeof lastItem[0] !== 'undefined' ? lastItem[0]['index'] : 0;
	};

	this.findFrom = function (index) {
		var result = [];

		for (var i = items.length - 1; i >= 0; i--) {
			var item = items[i];

			if (item.index > index) {
				result.unshift(item);
			} else {
				break;
			}
		}

		return result;
	}
}

module.exports = Master;
