GLOBAL.Module = new Static({
	prefix: null,
	path: "js/",

	requested: [],
	included: [],

	definitionName: null,
	definitionUsed: false,

	subscribers: {},

	define: function () {
		var self = this;
		var definition = Array.prototype.pop.call(arguments);
		var dependencies = Array.prototype.slice.call(arguments, 0);
		var definitionName = this.definitionName;

		this.definitionUsed = true;

		if (typeof compressedFiles != 'undefined') {
			definition();
		} else {

			setTimeout(function () {

				self.include(dependencies, function () {
					try {
						definition();
					}
					catch (e) {
						console.error("FAILED", definitionName);
						throw e;
					}

					self.included.push(definitionName);
					self.emit(definitionName);
				});

			}, 0);

			//console.log(dependencies);


		}
	},

	include: function (dependency, callback) {
		var self = this;
		var dependencies;

		if (!Util.isArray(dependency)) {
			dependencies = [dependency];
		} else {
			dependencies = dependency;
		}

		var counter = dependencies.length + 1;

		var execute = function () {
			counter--;

			if (!counter) {
				if (Util.isFunction(callback)) {
					callback();
				}
			}
		};

		for (var i = 0; i < dependencies.length; i++) {
			dependency = dependencies[i];

			this.on(dependency, execute);

			this.load(dependency);
		}

		execute();
	},

	load: function (name) {
		var self = this;

		if (Util.defined(name)) {
			this.emit(name);
		} else if (!~this.requested.indexOf(name)) {
			this.requested.push(name);

			Xhr.request({
				prefix: this.prefix,
				url: this.path + name.replace(/\./g, "/") + ".js"
			}, function (code, error) {
				if (error) {
					self.requested.splice(self.requested.indexOf(name));

					throw "Request for + " + name + " class failed";
				}

				self.definitionName = name;
				self.definitionUsed = false;

				eval(code);

				if (!self.definitionUsed) {
					self.included.push(name);
					self.emit(name);
				}
			});
		} else if (~this.included.indexOf(name)) {
			this.emit(name);
		}
	},

	on: function (name, callback) {
		if (!this.subscribers[name]) {
			this.subscribers[name] = [];
		}

		this.subscribers[name].push(callback);
	},

	emit: function (name) {
		var subscribers = this.subscribers[name];

		if (subscribers) {
			var callback;

			while (callback = subscribers.shift()) {
				callback();
			}
		}
	}
});