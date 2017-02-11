var cluster = require('cluster');

var config = {
	port: 1337,
	publishport: 1338
}

if(cluster.isWorker) {
	var Worker = require("./worker");
	new Worker(config, cluster);
	return;
}


/*
 * Master
 */
var http = require('http');
var qs = require('querystring');

var Master = function(options) {
	this.options = null;

	this.init = function(options) {
		this.options = options;

		this.initWorkers();
		this.initServer();

		var self=this;
		setInterval(function() {
			self.notify('all','eeee');
		}, 5000);
	}

	this.each = function(callback) {
		for (var id in cluster.workers) {
			if (cluster.workers.hasOwnProperty(id)) {
				callback(cluster.workers[id]);
			}
		}
	}

	this.initWorkers = function() {
		var numCPUs = 1; //require('os').cpus().length;

		for (var i = 0; i < numCPUs; i++) {
			cluster.fork();
		}

		cluster.on('exit', function (worker, code, signal) {
			console.log('worker ' + worker.process.pid + ' died');
		});
	}

	this.initServer = function() {
		http.createServer(this.onRequest.bind(this)).listen(this.options.publishport, function () {
			console.log("master listen port", this.options.publishport);
		}.bind(this));
	}

	this.onRequest = function(req, res) {
		if (req.method !== 'POST') {
			res.end("ok/ok");
			return;
		}

		var self = this;
		readPostData(req, function (data) {

			if (data === null) {
				res.statusCode = 500;
				res.end("too large");
				return;
			} else {
				res.statusCode = 200;
				res.end("ok");
			}

			if (data.tokens && data.data) {
				self.notify(data.tokens, data.data);
			}
		});
	}

	this.notify = function(tokens, data) {
		this.each(function(worker) {
			worker.send({cmd:"notify", tokens: tokens, data: data});
		})
	}

	this.init(options);
}; new Master(config);


function readPostData(req, callback) {
	var body = '';
	var limitBytes = 1 * 1024 * 1024; //1mb
	var makeRead = true;

	function finish(body, callback) {
		body = body || {};

		try {
			body = decodeURIComponent(body);
			body = qs.parse(body) || {};
			//body = JSON.parse(body) || {};
		}
		catch (e) {
			body = {};
		}

		callback(body);
	}

	req.on('data',function (chunk) {

		if (!makeRead) {
			return finish(null, callback);
		}

		body += chunk;

		if (body.length > limitBytes) {
			makeRead = false;
			body = null;
		}
	}).on('end', function () {
		finish(body, callback);
	});
}