require("./AMI.js");

GLOBAL.AMIActions = Class(AMI, {
	eventsMode: "off",
	listingChannels: false,

	listChannels: function (callback) {
		var self = this;
		var channels = [];

		this.listingChannels = true;

		var channelCallback = function (channelData) {
			channels.push(channelData);
		};

		this.action("CoreShowChannels", null, function (response) {
			if (response == "Success") {
				self.on("AMI.CoreShowChannel", channelCallback);

				self.once("AMI.CoreShowChannelsComplete", function () {
					self.off("AMI.CoreShowChannel", channelCallback);

					callback(channels);

					self.listingChannels = false;
				});
			} else {
				self.listingChannels = false;

				callback(channels, true);
			}
		});
	},

	originate: function (args, callback) {
		return this.action("Originate", args, callback);
	}
});
