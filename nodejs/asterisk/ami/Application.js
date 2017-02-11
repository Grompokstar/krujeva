require("./Core.js");
require("./Util.js");
require("./Events.js");

require("./Master.js");

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

//process.on("SIGINT", function () {
//	ami.close();
//	http.close();
//});

var master = new Master(options);

master.run();
