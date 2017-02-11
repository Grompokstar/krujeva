require("./Core.js");
require("./Util.js");
require("./Events.js");
require("./HttpServer.js");
require("./AMI.js");

var options = {
	port: "10080",
	amihost: "localhost",
	amiport: 5038,
	amiuser: "ami",
	amisecret: "ami"
};

var opt = require("node-getopt").create([
	['p', 'port=ARG', 'Порт'],
	['', 'amihost=ARG', 'Хост AMI'],
	['', 'amiport=ARG', 'Порт AMI'],
	['', 'amiuser=ARG', 'Пользователь AMI'],
	['', 'amisecret=ARG', 'Пароль AMI'],
	['h', 'help', 'Справка']
]).bindHelp().parseSystem();

options = Util.merge(options, opt.options);

var http = new HttpServer();
var ami = new AMI();

process.on("SIGINT", function () {
	ami.close();
	http.close();
});

ami.connect({
	host: options.amihost,
	port: options.amiport
}).on("connected", function () {
	ami.login(options.amiuser, options.amisecret, function (response, data) {
		if (response != "Success") {
			console.log("login failed");

			ami.logout(function () {
				ami.close();
			});

			http.close();
		} else {
			console.log("logged in");
		}
	});
});

http.listen({ port: 10080 }).on("request", function (args) {
	var command = args.command;
	var method = args.method;
	var data = args.data;

	function getCommand(index) {
		return index < command.length ? command[index] : null;
	}

	if (command.length) {
		switch (method) {
			case "GET":
				switch (getCommand(0)) {
					case "logout":
						ami.logout(function () {
							ami.close();
						});

						http.close();

						break;
				}
				break;
			case "POST":
				switch (getCommand(0)) {
					case "calls":
						switch (getCommand(1)) {
							case "originate":
								ami.originate({
									Exten: data.extension,
									Context: data.context,
									Priority: data.priority,
									Channel: data.channel
								}, function () {
									console.log(arguments);
								});
								break;
							case "answer":
								break;
						}
						break;
				}
				break;
		}
	}
});
