global.Config = require(absolutePath + "/config.json");

var redisClient = require("redis").createClient(Config.redis.port, Config.redis.host);
var pg = require('pg');
var pqClient = new pg.Client(Config.connection);

redisClient.on("error", function (err) {
	console.log("Error " + err);
});

pqClient.connect(function (err) {
	if (err) {
		return console.error('error fetching client from pool', err);
	}
});

global.senderSockets = {};
global.indexImeiSenderSocket = {};
global.senderSocketIndex = 0;

global.listenSockets = {};
global.listenSocketIndex = 0;

global.countListeners = function () {
	console.log('countListeners', Object.keys(listenSockets).length);
};

global.countSenders= function () {
	console.log('countSenders', Object.keys(senderSockets).length);
};

global.sendClients = function (imei, data) {

	for (var i in listenSockets) if (listenSockets.hasOwnProperty(i)) {

		if (listenSockets[i]['imei'] == imei) {

			listenSockets[i]['socket'].write(data);

		}

	}
};

global.onSoketClose = function (socket, callback) {
	socket.on("error", callback);
	socket.on("close", callback);
};

global.isArray = function (value) {
	return Object.prototype.toString.call(value) === "[object Array]";
};

global.each = function (object, callback, asArray) {
	if (asArray) {
		for (var i = 0, count = object.length; i < count; i++) {
			if (callback(object[i], i) === false) {
				break;
			}
		}
	} else {
		for (var key in object) {
			if (object.hasOwnProperty(key)) {
				if (callback(object[key], key) === false) {
					break;
				}
			}
		}
	}
};

global.formFirstBuffer = function (data) {
	var headers = data.toString('utf-8').split("\r\n");
	var buffer = '';
	var findContentType = false;

	if (!isArray(headers)) {
		return null;
	}

	each(headers, function (header) {

		buffer += header+"\r\n";

		if (~header.indexOf("Content-Type")) {

			findContentType = true;

			return false;
		}

	});

	if (!findContentType) {
		return null;
	}

	return new Buffer(buffer.toString("binary"), "binary");
};

global.getSenderImei = function (data) {
	var imei = null;

	var headers = data.toString('utf-8').split("\r\n");

	if (!isArray(headers)) {
		return null;
	}

	each(headers, function (header) {

		if (~header.indexOf("imei:")) {

			imei = header.replace(/imei: /g, '').replace(/ /g, '');

			return false;
		}

	});

	return imei;
};

global.getImei = function (data) {
	var imei = null;

	var headers = data.toString('utf-8').split("\n");

	if (isArray(headers)) {

		each(headers, function (header) {

			if (~header.indexOf("GET")) {

				var imeis = header.replace(/GET /g, '').replace(/\//g, '').split(' ');

				if (typeof imeis[0] !== "undefined") {

					imei = imeis[0];

				}

			}

		});

	}

	return imei;
};

global.queryRows = function (query, args, callback) {

	pqClient.query('select system.numseqget($1)', ['message'], function (err, result) {

		if (err) {
			console.error('error running query', err);
			callback(null);
			return;
		}

		callback(result.rows);
	});
};

global.notifyPhone = function (imei, startVideo) {

	queryRows('select system.numseqget($1)', ['message'], function (items) {

		var index = items[0]['numseqget'];

		redisClient.publish(Config.redis.channel, JSON.stringify({
			index: index,

			data: {
				event: "Transport.Apps.Praktika.Video." + (startVideo ? 'Start' : 'Stop'),
				data: null
			},

			callback: 'function (context, data) {if (context.phone && context.phone.imei == "' + imei + '"){return true;} return false;}'
		}));

	});
};

global.makeUnlistenNotifyPhone = function (imei) {
	var listenImei = false;

	each(listenSockets, function (socketObj) {

		if (socketObj.imei == imei) {

			listenImei = true;

			return false;

		}

	});

	if (!listenImei) {
		notifyPhone(imei, false);
	}
};

global.redisGet = function (key, callback) {
	redisClient.get(key, callback);
};

process.on('uncaughtException', function (err) {
	console.log('MYCaught exception: ' + err);
	console.log(err.stack);
});