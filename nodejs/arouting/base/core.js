global.Config = require(absolutePath + "/config.json");

var pg = require('pg');
var pqClient = new pg.Client(Config.connection);

pqClient.connect(function (err) {
	if (err) {
		return console.error('error fetching client from pool', err);
	}
});

global.onResClose = function (res, callback) {
	res.on("error", callback);
	res.on("close", callback);
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

global.queryRows = function (query, args, callback) {

	args = args || [];

	pqClient.query(query, args, function (err, result) {

		if (err) {
			console.error('error running query', err);
			callback([]);
			return;
		}

		callback(result.rows);
	});
};

global.queryRow = function (query, args, callback) {

	queryRows(query, args, function (result) {

		if (typeof result[0] == 'undefined') {

			callback(null);

		} else {

			callback(result[0]);
		}
	});
};

global.queryScalar = function (query, args, callback) {

	queryRow(query, args, function (result) {

		if (result !== null) {

			var key = Object.keys(result)[0];

			result = result[key];
		}

		callback(result);
	});

};

process.on('uncaughtException', function (err) {
	console.log('MYCaught exception: ' + err);
	console.log(err.stack);
});