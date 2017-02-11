require("./Core.js");
require("./Util.js");
require("./Events.js");
require("./HttpServer.js");
require("./Master.js");

var options = {
	port: "10080",
	sockhost: "freeswitch.shire.local",
	sockport: 8021,
	sockauth: "ClueCon"
};

var opt = require("node-getopt").create([
	['p', 'port=ARG', 'Порт'],
	['', 'sockhost=ARG', 'Хост Event Socket'],
	['', 'sockport=ARG', 'Порт Event Socket'],
	['', 'sockauth=ARG', 'Пароль Event Socket'],
	['h', 'help', 'Справка']
]).bindHelp().parseSystem();

options = Util.merge(options, opt.options);

console.log(options);

var master = new Master();

master.run();