require("./Core.js");
require("./Util.js");
require("./Events.js");

require("./AMIActions.js");

var amiOptions = {
	host: process.argv[2],
	port: process.argv[3],
	amiuser: process.argv[4],
	amisecret: process.argv[5]
};

var ami = new AMIActions(amiOptions);

process.on("message", function (args) {
	switch (args.message) {
		case "action":
			runAction(args.action, args.data);
			break;
		case "close":
			ami.close();
			process.disconnect();
			break;
	}
});

function runAction(action, data) {
	switch (action) {
		case "Calls.Originate":
			ami.originate(createParams(data, {
				"Exten": "extension",
				"Context": "context",
				"Priority": "priority",
				"Channel": "channel",
				"CallerID": "callerId"
			}), function () {
				console.log(arguments);
			});

			break;
	}
}

function createParams(source, map) {
	var params = {};

	Util.each(map, function (key, name) {
		if (source[key] !== undefined) {
			params[name] = source[key];
		}
	});

	return params;
}