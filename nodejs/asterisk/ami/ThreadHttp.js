require("./Core.js");
require("./Util.js");
require("./Events.js");

require("./HttpServer.js");

var port = process.argv[2];

console.log("Starting HTTP Worker with port", port);

var http = new HttpServer({ port: port });

http.listen().on("request", function (args) {
	var command = args.command;
	var method = args.method;
	var data = args.data;
	var action = null;

	function getCommand(index) {
		return (Util.isArray(command) && index < command.length) ? command[index] : null;
	}

	console.log(command, method, data);

	if (command.length) {
		switch (method) {
			case "GET":
				switch (getCommand(0)) {
					case "logout":
						action = "System.Logout";
						break;
				}
				break;
			case "POST":
				switch (getCommand(0)) {
					case "system":
						switch (getCommand(1)) {
							case "close":
								action = "System.Close";
								break;
						}

						break;
					case "calls":
						switch (getCommand(1)) {
							case "originate":
								action = "Calls.Originate";
								break;
						}
						break;
				}
				break;
		}
	}

	if (action) {
		process.send({
			message: "action",
			action: action,
			data: data
		});
	}
});

process.on("message", function (args) {
	switch (args.message) {
		case "close":
			http.close();
			process.disconnect();
			break;
	}
});
