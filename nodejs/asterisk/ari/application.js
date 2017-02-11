var WebSocketClient = require("websocket").client;
var client = new WebSocketClient();

client.on("connectFailed", function (error) {
	console.log("connection error", error.toString());
});

client.on("connect", function (connection) {
	console.log("connected");

	connection.on("error", function (error) {
		console.log("connection dropped", error.toString());
	});

	connection.on("close", function () {
		console.log("connection closed");
	});

	connection.on("message", function (message) {
		if (message.type === "utf8") {
			console.log("message");
			console.log(message.utf8Data);
		} else {
			console.log("unknown message");
			console.log(message);
		}
	});
});

client.connect("ws://pbx.shire.local:8080/ari/events?app=emergency&api_key=glonass:1q2w3e4r");
