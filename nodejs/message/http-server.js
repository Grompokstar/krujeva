var HTTP = require("http");
var QueryString = require("querystring");
var URL = require("url");
var EventEmitter = require("events").EventEmitter;
var Util = require("util");

var HttpServer = function (options) {
	EventEmitter.call(this);

	var self = this;

	var listening = false;
	var shutdown = false;

	this.options = options;

	this.server = HTTP.createServer();

	this.server.on("request", function (request, response) {
		onRequest(request, response);
	});

	this.server.on("listening", function () {
		listening = true;
	});

	this.server.on("error", function (error) {
		console.log("listening error", error);
	});

	this.server.on("close", function () {
		listening = false;

		if (!shutdown) {
			console.log("listen failed, restoring...");

			relisten();
		}
	});

	this.listen = function () {
		this.server.listen(this.options.port, this.options.host);
	};

	function relisten() {
		setTimeout(function () {
			self.listen();
		}, self.relistenTimeout);
	}

	this.close = function () {
		shutdown = true;

		this.server.close();
	};

	function onRequest(request, response) {
		var url = URL.parse(request.url);

		var params = "";

		function finish() {
			self.emit("request", {
				path: url.pathname,
				data: params,
				method: request.method,
				request: request,
				response: response
			});
		}

		if (request.method == "GET") {
			params = QueryString.parse(url.query);
			finish();
		} else if (~["POST", "DELETE"].indexOf(request.method)) {
			request.on("data", function (data) {
				params += data;
			});

			request.on("end", function () {
				params  = QueryString.parse(params);

				finish();
			});
		} else {
			params = {};

			finish();
		}
	}
};

Util.inherits(HttpServer, EventEmitter);

module.exports = HttpServer;
