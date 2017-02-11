require("./Core.js");
require("./Util.js");
require("./Events.js");

require("./AMIEvents.js");

var amiOptions = {
	host: process.argv[2],
	port: process.argv[3],
	amiuser: process.argv[4],
	amisecret: process.argv[5]
};

var ami = new AMIEvents(amiOptions);

process.on("message", function (args) {
	switch (args.message) {
		case "close":
			ami.close();
			process.disconnect();
			break;
	}
});
