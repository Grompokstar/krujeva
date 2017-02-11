Module.define(function () {
	NS("Security");

	Security.Security = Class({
		context: null,

		initialize: function (context) {
			this.context = context;
		},

		init: function (context) {
			var self = this;

			if (context) {
				this.context.update(context);
			} else {
				var result = Xhr.call("Security/Web/Context");

				if (result.success && result.data) {
					this.context.update(result.data);
				}
			}

			return this;
		},

		check: function (key, mode) {
			if (!this.context || !this.context.role) {
				return false;
			}

			if (this.context.role.name == "root") {
				return true;
			}

			if (mode === undefined) {
				mode = Security.AccessMode.Execute;
			}

			var granted = Util.coalesce(this.context.role.access[key], []);

			return !!~granted.indexOf(mode);
		},

		isRoot: function () {
			return this.context && this.context.role && this.context.role.name == "root";
		},

		signIn: function (login, password, callback) {
			var self = this;

			return Xhr.call("Security/Web/SignIn", { login: login, password: password }, function (result) {
				var ret = false;

				if (result.success && result.data) {
					self.context.update(result.data);
					ret = true;
				}

				Util.call(callback, self, [ret]);
			});
		},

		signOut: function (callback) {
			var self = this;

			return Xhr.call("Security/Web/SignOut", function (result) {
				self.context.update(result.data);

				Util.call(callback, self);
			});
		}
	});
});
