GLOBAL.Util = Static({
	clone: function (object, options) {
		if (typeof(options) != "object") {
			options = {};
		}

		options.circular = options.circular === undefined ? true : options.circular;

		if (!object || typeof(object) != "object") {
			return object;
		}

		var result = typeof(object.pop) == "function" ? [] : {};
		var key, value;

		if (!options.replaces) {
			options.replaces = {
				except: [],
				replace: []
			}
		}

		options.replaces.except.push(object);
		options.replaces.replace.push(result);

		for (key in object) {
			if (object.hasOwnProperty(key)) {
				value = object[key];

				if (value && typeof(value) == "object") {
					var idx = options.replaces.except.indexOf(value);

					if (idx != -1) {
						if (options.circular) {
							result[key] = options.replaces.replace[idx];
						}
					} else {
						result[key] = this.clone(value, options);
					}
				} else {
					result[key] = value;
				}
			}
		}

		return result;
	},

	equal: function (left, right) {
		if (typeof(left) != "object" || typeof(right) != "object" || !left || !right) {
			return left == right;
		}

		var self = this;
		var result = true;

		this.each(left, function (value, name) {
			if (!self.equal(left[name], right[name])) {
				result = false;
				return false;
			}

			return true;
		});

		return result;
	},

	isEmpty: function (object) {
		return !Object.keys(object).length;
	},

	isValue: function (value) {
		return value !== null || value !== undefined;
	},

	object: function (object, clone) {
		if (object && typeof(object) == "object") {
			return clone ? this.clone(object) : object;
		}

		return {};
	},

	array: function (object, clone) {
		if (object && this.isArray(object)) {
			return clone ? this.clone(object) : object;
		}

		return [];
	},

	merge: function (target, source, clone) {
		if (!target || typeof(target) != "object") {
			target = {};
		}

		if (!source || typeof(source) != "object") {
			source = {};
		}

		for (var key in source) {
			if (source.hasOwnProperty(key)) {
				target[key] = clone ? this.clone(source[key]) : source[key];
			}
		}

		return target;
	},

	defaults: function (defaults, options) {
		var self = this;

		if (!options || typeof(options) != "object") {
			options = {};
		}

		this.each(defaults, function (value, name) {
			options[name] = options[name] === undefined ? value : options[name];
		});

		return options;
	},

	options: function (options, names) {
		var opts = {};
		var isArray = this.isArray(names);

		if (options && typeof(options) == "object" && names && typeof(names) == "object") {
			for (var prop in names) {
				if (names.hasOwnProperty(prop)) {
					var name = names[prop];
					var val = options[isArray ? name : prop];

					if (val !== undefined) {
						opts[name] = val;
					}
				}
			}
		}

		return opts;
	},

	call: function (callback, context, args) {
		if (!args) {
			args = [];
		}

		if (Util.isFunction(callback)) {
			return callback.apply(context ? context : GLOBAL, args);
		}
	},

	coalesce: function () {
		for (var i = 0, count = arguments.length; i < count; i++) {
			var argument = arguments[i];

			if (argument !== null && argument !== undefined) {
				return argument;
			}
		}

		return null;
	},

	log: function () {
		Array.prototype.unshift.call(arguments, (new Date()).toString());

		console.log.apply(console, arguments);
	},

	each: function (object, callback, asArray) {
		if (asArray) {
			for (var i = 0, count = object.length; i < count; i++) {
				if (callback(object[i], i) === false) {
					break;
				}
			}
		} else {
			for (var key in object) {
				if (object.hasOwnProperty(key)) {
					if (callback(object[key], key) === false) {
						break;
					}
				}
			}
		}
	},

	defined: function (name) {
		var nsList = name.split(".");
		var nsType;
		var ns = "";

		for (var i = 0; i < nsList.length; i++) {
			if (ns != "") {
				ns += ".";
			}

			ns += nsList[i];

			eval("nsType = typeof(" + ns + ");");

			if (nsType == "undefined") {
				return false;
			}
		}

		return true;
	},

	isArray: function (value) {
		return Object.prototype.toString.call(value) === "[object Array]";
	},

	isObject: function (value) {
		return value && typeof(value) == "object";
	},

	isFunction: function (value) {
		return typeof(value) == "function";
	},

	create: function (className, args) {
		var object;
		var cmd = "object = new " + className + "(";

		if (args) {
			for (var i = 0; i < args.length; i++) {
				if (i > 0) {
					cmd += ",";
				}

				cmd += "args[" + i + "]";
			}
		}

		cmd += ");";

		eval(cmd);

		return object;
	},

	parseJSON: function (json) {
		try {
			return JSON.parse(json);
		} catch (e) {
			return null;
		}
	},

	async: function (callback, context, timeout) {
		timeout = this.coalesce(timeout, 0);

		return setTimeout(function () {
			if (context) {
				callback.call(context);
			} else {
				callback();
			}
		}, timeout);
	},

	delayed: function (timeout, callback, object) {
		var method = this.delayed;

		if (!method.pending) {
			method.pending = [];
		}

		timeout = this.coalesce(timeout, 100);

		var subscribe = function (pending) {
			pending.id = setTimeout(function () {
				for (var k = 0; k < method.pending.length; k++) {
					if (method.pending.callback == pending.callback && method.pending.object == pending.object) {
						method.pending.splice(k, 1);
						break;
					}
				}

				if (pending.object) {
					pending.callback.call(pending.object);
				} else {
					pending.callback();
				}
			}, pending.timeout);
		};

		for (var i = 0; i < method.pending.length; i++) {
			var pending = method.pending[i];

			if (pending.callback == callback && pending.object == object) {
				clearTimeout(pending.id);
				subscribe(pending);

				return pending.id;
			}
		}

		pending = {
			callback: callback,
			object: object,
			timeout: timeout
		};

		subscribe(pending);

		method.pending.push(pending);

		return pending.id;
	},

	denull: function (object, clone) {
		var self = this;

		if (clone) {
			object = this.clone(object);
		}

		Util.each(object, function (value, name) {
			if (value === null) {
				delete object[name];
			} else if (typeof(value) == "object") {
				self.denull(value);
			}
		});

		return object;
	},

	String: {
		if: function (value, string) {
			return value && String(value).trim().length ? string : "";
		},

		prependIf: function (value, prepend) {
			if (value && value.length) {
				return "" + prepend + value;
			}

			return "";
		},

		appendIf: function (value, append) {
			if (value && value.length) {
				return "" + value + append;
			}

			return "";
		},

		coalesce: function () {
			for (var i = 0, count = arguments.length; i < count; i++) {
				var argument = arguments[i];

				if (argument && argument.length) {
					return argument;
				}
			}

			return "";
		},

		join: function (array, delimiter) {
			delimiter = Util.coalesce(delimiter, ", ");
			var items = [];

			Util.each(array, function (item) {
				if (item) {
					item = String(item).trim();

					if (item.length) {
						items.push(item);
					}
				}
			});

			return items.join(delimiter);
		},

		repeat: function (string, count) {
			var result = "";

			while (count--) {
				result += string;
			}

			return result;
		}
	},

	Array: {
		reduce: function (array, callback, initial) {
			var value = initial;

			for (var i = 0, count = array.length; i < count; i++) {
				value = callback(value, array[i]);
			}

			return value;
		}
	}
});
