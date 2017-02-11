var HTTP = require("http");
var QueryString = require("querystring");
var URL = require("url");

GLOBAL.HttpServer = Class(Events, {
	server: null,

	listening: false,
	shutdown: false,

	relistenTimeout: 1000,

	options: {},

	initialize: function (options) {
		var self = this;

		this.options = options;

		this.server = HTTP.createServer();

		this.server.on("request", function (request, response) {
			self.onRequest(request, response);
		});

		this.server.on("listening", function () {
			self.listening = true;
		});

		this.server.on("error", function (error) {
			console.log("listening error", error);
		});

		this.server.on("close", function () {
			self.listening = false;

			if (!self.shutdown) {
				console.log("listen failed, restoring...");

				self.relisten();
			}
		});
	},

	listen: function () {
		this.server.listen(this.options.port, this.options.host);

		return this;
	},

	relisten: function () {
		var self = this;

		setTimeout(function () {
			self.listen();
		}, this.relistenTimeout);

		return this;
	},

	close: function () {
		this.shutdown = true;

		this.server.close();
	},

	onRequest: function (request, response) {
		var self = this;
		var url = URL.parse(request.url);

		var params = "";

		function finish() {
			self.emit("request", {
				command: url.pathname.replace(/^\/*/, "").replace(/\/*$/, "").split("/"),
				data: params,
				method: request.method
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

		response.end("OK");
	}
});
