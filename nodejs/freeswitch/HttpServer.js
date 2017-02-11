var HTTP = require("http");
var QueryString = require("querystring");
var URL = require("url");

GLOBAL.HttpServer = Class(Events, {
	server: null,

	initialize: function () {
		var self = this;

		this.server = HTTP.createServer();

		this.server.on("request", function (request, response) {
			self.onRequest(request, response);
		});
	},

	listen: function (options) {
		this.server.listen(options.port, options.host);

		return this;
	},

	close: function () {
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
