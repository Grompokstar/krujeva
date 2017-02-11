Module.define(function () {
	NS("Security");

	Security.Context = Class({
		role: null,
		user: null,
		sessionId: null,
		userProfile: null,

		update: function (data) {
			this.role = data.role;
			this.user = data.user;
			this.sessionId = data.sessionId;
			this.userProfile = data.userProfile;

			return this;
		}
	});
});
