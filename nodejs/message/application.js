var opt = require("node-getopt").create([
	['', 'ml=ARG', 'Хост мастера для получения сообщений с бэкенда.'],
	['', 'mp=ARG', 'Порт мастера для получения сообщений с бэкенда.'],
	['', 'mq=ARG', 'Количество последних кэшируемых сообщений.'],
	['', 'wl=ARG', 'Хост воркера для подключения пользователей.'],
	['', 'wp=ARG', 'Порт воркера для подключения пользователей.'],
	['', 'prefix=ARG', 'Префикс для SockJS.'],
	['', 'mode=ARG', 'http или redis.'],
	['', 'storage=ARG', 'memcached или redis.'],
	['', 'sh=ARG', 'Кеш-хост.'],
	['', 'sp=ARG', 'Кеш-порт.'],
	['', 'channel=ARG', 'Redis-канал.'],
	['', 'tag=ARG', 'Тэг контекстов.'],
	['h', 'help', 'Справка']
]).bindHelp().parseSystem();

global.Cluster = require("cluster");

var Master = require("./master");
var Worker = require("./worker");

function readStorage(opt) {
	var storage = {};

	if (opt.options.storage) {
		storage.type = opt.options.storage;
	}

	if (opt.options.sh) {
		storage.host = opt.options.sh;
	}

	if (opt.options.sp) {
		storage.port = +opt.options.sp;
	}

	return storage;
}

if (Cluster.isMaster) {
	var options = {};

	if (opt.options.ml) {
		options.listen = opt.options.ml;
	}

	if (opt.options.mp) {
		options.port = +opt.options.mp;
	}

	if (opt.options.mq) {
		options.queueSize = +opt.options.mq;
	}

	if (opt.options.channel) {
		options.channel = opt.options.channel;
	}

	var storage = readStorage(opt);

	options.storage = storage;

	switch (opt.options.mode) {
		case "http":
			options.mode = "http";
			break;
		case "redis":
			if (storage.type = "redis") {
				options.mode = "redis";
			}
			break;
	}

	var master = new Master(options);
} else {
	var options = {};

	if (opt.options.prefix) {
		options.prefix = opt.options.prefix;
	}

	if (opt.options.wl) {
		options.listen = opt.options.wl;
	}

	if (opt.options.wp) {
		options.port = +opt.options.wp;
	}

	var storage = readStorage(opt);

	if (opt.options.tag) {
		storage.tag = opt.options.tag;
	}

	options.storage = storage;

	var worker = new Worker(options);
}
