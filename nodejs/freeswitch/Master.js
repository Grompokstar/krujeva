var ChildProcess = require("child_process");

GLOBAL.Master = Class({
	workers: [],

	initialize: function () {
		console.log("Master initialized");
	},

	run: function () {
		var worker = ChildProcess.fork("./CommandWorker", ["qwe", 1]);

		this.workers.push(worker);
	}
});
