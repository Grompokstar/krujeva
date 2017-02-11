var http = require('http');


function Worker(options, cluster) {
	this.options = null;
	this.cluster = null;

	this.connections = {};
	this.token2id = {};

	this.init = function(options, cluster) {
		this.options = options;
		this.cluster = cluster;

		http.createServer(this.onRequest.bind(this)).listen(this.options.port, function() {
			console.log("worker", this.cluster.worker.id, "listen port", this.options.port);
		}.bind(this));

		setInterval(this.count.bind(this), 2000);

		process.on('message', function (options) {
			if(options.cmd === "notify") {
				this.notify(options.tokens, options.data);
			}
		}.bind(this));
	}

	this.notify = function(tokens, data) {
		console.log("notify");

		//@TODO tokens

		for(var i in this.connections) {
			var connection = this.connections[i];
			connection.res.end(data);
			this.onCloseRequest(connection.res);
		}
	}

	this.getConnections = function(tokens) {
		if (tokens == "all") {
			return this.connections;
		}

		//@TODO
	}

	this.onRequest = function(req, res) {
		if (req.method == "POST") {
			res.statusCode = 404;
			res.end("bad request");
			return;
		}

		var cookie = parseCookies(req.headers.cookie);
		res.token = cookie.PHPSESSID || this.id();
		res.id = this.id();

		if (!res.token) {
			res.statusCode = 404;
			res.end("bad request (token)");
			return;
		}

		if (!this.token2id[res.token]) {
			this.token2id[res.token] = [];
		}

		this.token2id[res.token].push(res.id);

		this.connections[res.id] = {
			res: res,
			token: null
		};

		var self = this;
		res.on("close", function() {
			self.onCloseRequest(this);
		});
	}

	this.onCloseRequest = function(res) {
		delete this.connections[res.id];

		if (this.token2id[res.token]) {
			var index = this.token2id[res.token].indexOf(res.id);

			if(index != -1) {
				this.token2id[res.token].splice(index, 1);

				if (!this.token2id[res.token].length) {
					delete this.token2id[res.token];
				}
			}
		}

		var self = this;
		res.removeListener("close", function () {
			self.onCloseRequest(this);
		});

		res = null;
	}

	this.id = function() {
		function rand(min, max) {
			var rand = min - 0.5 + Math.random() * (max - min + 1);
			return Math.round(rand);
		}

		var result = '';
		var abc = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
		var count = (abc.length - 1);
		var to = rand(5, 20);

		for (var i = 0; i < to; i++) {
			result += abc[rand(0, count)];
		}

		result += rand(2, 3000);

		for (var i = 0; i < to; i++) {
			result += abc[rand(0, count)];
		}

		var date = new Date();
		return result + (date.getTime());
	}

	this.count = function() {
		console.log("count connections (" + this.cluster.worker.id + ")", Object.keys(this.connections).length);
	}

	this.init(options, cluster);
}

module.exports = Worker;

function parseCookies(cookie) {
	var list = {};

	if (typeof cookie !== "string") {
		return list;
	}

	var array = cookie.split(';');
	for (var i in array) if (array.hasOwnProperty(i)) {
		var parts = cookie.split('=');

		if (typeof parts[0] != 'undefined' && typeof parts[1] != 'undefined') {
			list[parts[0]] = parts[1];
		}

		parts = null;
	}

	array = null;
	return list;
}