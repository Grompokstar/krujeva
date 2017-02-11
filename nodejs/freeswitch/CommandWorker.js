require("./Core.js");
require("./Util.js");
require("./Events.js");

GLOBAL.CommandWorker = Class({
	initialize: function () {
		console.log("CommandWorker initialized", process.argv);
	}
});

var worker = new CommandWorker();
