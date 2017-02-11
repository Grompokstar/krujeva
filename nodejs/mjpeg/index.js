var net = require('net');

global.absolutePath = __dirname;
require('./base/core');



//notifyPhone();
//return;

/*redisGet("e2fpvlmsqeoevcq04t4on9mcs2__SecurityContext", function (error, data) {
	console.log(arguments);
});*/

net.createServer(function (socket) {
	var socketIndex = socket.SOKET_INDEX = ++senderSocketIndex;

	senderSockets[socketIndex] = {
		socket: socket,
		imei: null,
		index: socketIndex,
		firstBuffer: null
	};

	countSenders();

	onSoketClose(socket, function () {

		if (typeof senderSockets[socket.SOKET_INDEX] !== 'undefined') {

			delete indexImeiSenderSocket[senderSockets[socket.SOKET_INDEX]['imei']];

		}

		delete senderSockets[socket.SOKET_INDEX];

		countSenders();
	});

	socket.on('data', function (data) {

		var socketObject = senderSockets[socket.SOKET_INDEX];

		if (!socketObject) {
			return;
		}

		if (!socketObject['firstBuffer']) {

			//var firstBuffer = formFirstBuffer(data);

			var imei = getSenderImei(data);

			if (!imei) {

				socket.end();

				return;
			}

			console.log("sender Imei", imei);

			socketObject['imei'] = imei;

			socketObject['firstBuffer'] = data;

			indexImeiSenderSocket[socketObject['imei']] = socket.SOKET_INDEX;
		}

		sendClients(socketObject['imei'], data);
	});

}).listen(Config.senderport);

net.createServer(function (socket) {
	var socketIndex = socket.SOKET_INDEX = ++listenSocketIndex;

	onSoketClose(socket, function () {

		var unlistenIMEI = null;

		var socketObject = listenSockets[socket.SOKET_INDEX];

		if (socketObject && socketObject.imei) {
			unlistenIMEI = socketObject.imei;
		}

		delete listenSockets[socket.SOKET_INDEX];

		countListeners();

		if (unlistenIMEI) {
			makeUnlistenNotifyPhone(unlistenIMEI);
		}

	});

	socket.on('data', function (data) {

		var imei = getImei(data);

		if (!imei) {

			socket.end();

			return;
		}

		listenSockets[socketIndex] = {
			socket: socket,
			imei: imei,
			index: socketIndex
		};

		countListeners();

		notifyPhone(imei, true);

		listenSockets[socket.SOKET_INDEX]['imei'] = imei;

		var senderSoketIndex = indexImeiSenderSocket[listenSockets[socket.SOKET_INDEX]['imei']];

		var senderSocket = senderSockets[senderSoketIndex];

		if (typeof senderSocket == 'undefined') {
			return;
		}

		if (!senderSocket.firstBuffer) {
			return;
		}

		socket.write(senderSocket.firstBuffer);
	});

}).listen(Config.listenport);