var GLOBAL = typeof(window) == "object" ? window : global;

(function () {
	GLOBAL.NS = function (name) {
		var nsList = name.split(".");
		var nsType;
		var ns = "";
		nsList.unshift("GLOBAL");

		for (var i = 0; i < nsList.length; i++) {
			if (ns != "") {
				ns += ".";
			}

			ns += nsList[i];

			eval("nsType = typeof(" + ns + ");");

			if (nsType != "object") {
				eval(ns + " = {};");
			}
		}
	};

	GLOBAL.Class = function () {
		var parents = arguments;

		var member;
		var i, k;
		var parent;
		var struct;

		var instance = function () {
			for (var property in instance.prototype) {
				if (instance.prototype.hasOwnProperty(property)) {
					if (typeof(instance.prototype[property]) != "function") {
						this[property] = clone(instance.prototype[property]);
					}
				}
			}

			this.Parent = classParent;
			this.ParentCall = classParentCall;
			this.ParentMethod = classParentMethod;
			this.CallMethod = classCallMethod;
			this.Call = classCall;
			this.Is = function (classObject) {
				return this.Class.Is(classObject);
			};

			this.Class = instance;

			if (this.initialize && typeof(this.initialize) == "function") {
				this.initialize.apply(this, arguments);
			} else {
				for (var i = parents.length; i > 0; i--) {
					if (typeof(parents[i - 1]) == "function" && parents[i - 1].prototype.initialize && typeof(parents[i - 1].prototype.initialize) == "function") {
						parents[i - 1].prototype.initialize.apply(this, arguments);
						break;
					}
				}
			}
		};

		for (i = 0; i < parents.length; i++) {
			parent = parents[i];

			if (typeof(parents[i]) == "function") {
				for (member in parent.prototype) {
					if (parent.prototype.hasOwnProperty(member)) {
						instance.prototype[member] = clone(parent.prototype[member]);

						if (typeof(instance.prototype[member]) == "function") {
							instance.prototype[member].methodName = member;
						}
					}
				}

				for (member in parent) {
					if (parent.hasOwnProperty(member)) {
						instance[member] = parent[member];

						if (typeof(instance[member]) == "function") {
							instance[member].methodName = member;
						}
					}
				}
			} else if (typeof(parents[i]) == "object") {
				for (member in parent) {
					if (parent.hasOwnProperty(member)) {
						instance.prototype[member] = clone(parent[member]);

						if (typeof(instance.prototype[member]) == "function") {
							instance.prototype[member].methodName = member;
						}
					}
				}
			} else {
				console.log("Illegal parent", parents[i]);
			}
		}

		for (var method in instance.prototype) {
			var methodFound = false;

			if (instance.prototype.hasOwnProperty(method)) {
				if (typeof(instance.prototype[method]) == "function") {
					for (i = parents.length; i > 0; i--) {
						if (typeof(parents[i - 1]) == "function") {
							parent = parents[i - 1];

							if (parent.prototype.hasOwnProperty(method)) {
								if (!methodFound) {
									methodFound = true;
								} else {
									if (typeof(parent.prototype[method]) == "function") {
										instance.prototype[method].parentMethod = parent.prototype[method];
									}
									break;
								}
							}
						} else if (typeof(parents[i - 1]) == "object") {
							struct = parents[i - 1];

							if (struct.hasOwnProperty(method)) {
								if (!methodFound) {
									methodFound = true;
								} else {
									if (typeof(struct[method]) == "function") {
										instance.prototype[method].parentMethod = struct[method];
									}
									break;
								}
							}
						}
					}
				}
			}
		}

		instance.Parents = [];

		for (i = 0; i < parents.length - 1; i++) {
			parent = parents[i];

			if (parent.Parents) {
				for (k = 0; k < parent.Parents.length; k++) {
					var parentParent = parent.Parents[k];

					if (instance.Parents.indexOf(parentParent) == -1) {
						instance.Parents.push(parentParent);
					}
				}
			}

			if (instance.Parents.indexOf(parent) == -1) {
				instance.Parents.push(parent);
			}
		}

		instance.Static = function (members) {
			if (members) {
				for (var member in members) {
					if (members.hasOwnProperty(member)) {
						var methodFound = false;

						instance[member] = members[member];

						if (typeof(instance[member]) == "function") {
							instance[member].methodName = member;

							for (i = parents.length - 1; i > 0; i--) {
								if (typeof(parents[i - 1]) == "function") {
									parent = parents[i - 1];

									if (parent.hasOwnProperty(member) && (typeof(parent[member]) == "function")) {
										instance[member].parentMethod = parent[member];
										break;
									}
								}
							}
						}
					}
				}
			}

			if (typeof(instance.initialize) == "function") {
				instance.initialize();
			}

			return this;
		};

		instance.Static.instance = instance;

		instance.Parent = classParent;
		instance.ParentCall = classParentCall;
		instance.ParentMethod = classParentMethod;
		instance.CallMethod = classCallMethod;
		instance.Call = classCall;
		instance.Is = classIs;

		if (typeof(instance.initialize) == "function") {
			instance.initialize();
		}

		return instance;
	};

	GLOBAL.Class.Is = function (object, classObject) {
		if (!classObject) {
			return false;
		}

		if (!object || (typeof(object) != "object" && typeof(object) != "function")) {
			return false;
		}

		if (object.Is) {
			return object.Is(classObject);
		}

		return false;
	};

	GLOBAL.Static = function () {
		var struct = {};
		var parents = arguments;
		var parent;

		for (var i = 0; i < arguments.length; i++) {
			var item = arguments[i];

			for (var member in item) {
				if (item.hasOwnProperty(member)) {
					struct[member] = clone(item[member]);

					if (typeof(struct[member]) == "function") {
						struct[member].methodName = member;
					}
				}
			}
		}

		for (var method in struct) {
			var methodFound = false;

			if (struct.hasOwnProperty(method)) {
				if (typeof(struct[method]) == "function") {
					for (i = parents.length; i > 0; i--) {
						if (typeof(parents[i - 1]) == "function") {
							parent = parents[i - 1];

							if (parent.hasOwnProperty(method)) {
								if (!methodFound) {
									methodFound = true;
								} else {
									if (typeof(parent[method]) == "function") {
										struct[method].parentMethod = parent[method];
									}
									break;
								}
							}
						} else if (typeof(parents[i - 1]) == "object") {
							parent = parents[i - 1];

							if (parent.hasOwnProperty(method)) {
								if (!methodFound) {
									methodFound = true;
								} else {
									if (typeof(parent[method]) == "function") {
										struct[method].parentMethod = parent[method];
									}
									break;
								}
							}
						}
					}
				}
			}
		}

		struct.Parents = [];

		for (i = 0; i < parents.length - 1; i++) {
			parent = parents[i];

			if (parent.Parents) {
				for (var k = 0; k < parent.Parents.length; k++) {
					var parentParent = parent.Parents[k];

					if (struct.Parents.indexOf(parentParent) == -1) {
						struct.Parents.push(parentParent);
					}
				}
			}

			if (struct.Parents.indexOf(parent) == -1) {
				struct.Parents.push(parent);
			}
		}

		struct.Parent = classParent;
		struct.ParentCall = classParentCall;
		struct.ParentMethod = classParentMethod;
		struct.CallMethod = classCallMethod;
		struct.Call = classCall;
		struct.Is = classIs;

		if (typeof(struct.initialize) == "function") {
			struct.initialize();
		}

		return struct;
	};

	function clone(object, options) {
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
						result[key] = clone(value, options);
					}
				} else {
					result[key] = value;
				}
			}
		}

		return result;
	}

	function classIs(classObject) {
		if (!classObject) {
			return false;
		}

		return this == classObject || this.Parents.indexOf(classObject) != -1;
	}

	function classParent() {
		if (classParent.caller.parentMethod) {
			return classParent.caller.parentMethod.apply(this, arguments);
		}

		return null;
	}

	function classParentCall() {
		if (classParentCall.caller.parentMethod) {
			return classParentCall.caller.parentMethod.apply(this, classParentCall.caller.arguments);
		}

		return null;
	}

	function classParentMethod() {
		if (classParentMethod.caller.parentMethod) {
			return classParentMethod.caller.parentMethod;
		}

		return null;
	}

	function classCallMethod() {
		var method = Array.prototype.shift.call(arguments);

		if (method) {
			method.apply(this, arguments);
		}
	}

	function classCall() {
		if (!arguments.length) {
			return null;
		}

		var className = Array.prototype.shift.call(arguments);
		var result = null;

		eval("result = " + className + ".prototype." + classCall.caller.methodName + ".apply(this, arguments);");

		return result;
	}
})();
