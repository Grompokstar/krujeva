function using() {
	var callback = Array.prototype.pop.call(arguments);
	var dependencies = [];

	if (arguments.length) {
		if (arguments.length == 1 && Util.isArray(arguments[0])) {
			dependencies = arguments[0];
		} else {
			for (var i = 0; i < arguments.length; i++) {
				dependencies.push(arguments[i]);
			}
		}

		Module.include(dependencies, callback);
	} else {
		callback();
	}
}

// Postgresql

function pgIntArrayDecode(string) {
	if (Util.isArray(string)) {
		return string;
	}

	if (!string || typeof(string) != "string") {
		return [];
	}

	string = string.replace(/[{}]/g, "");

	var array = string.length ? string.split(",") : [];

	for (var i = 0, count = array.length; i < count; i++) {
		array[i] = +array[i];

		if (isNaN(array[i])) {
			array[i] = 0;
		}
	}

	return array;
}

function pgIntArrayEncode(array) {
	if (typeof(array) == "string") {
		return array;
	}

	if (!array || !Util.isArray(array)) {
		return "{}";
	}

	for (var i = 0, count = array.length; i < count; i++) {
		array[i] = +array[i];

		if (isNaN(array[i])) {
			array[i] = 0;
		}
	}

	return "{" + array.join(",") + "}";
}

function pgArrayDecode(string) {
	if (Util.isArray(string)) {
		return string;
	}

	if (!string || typeof(string) != "string") {
		return [];
	}

	var array = [];
	var regexp = /[\{,]("(?:\\"|\\\\|[^"])*"|[^,"\\}]*)\}?/g;
	var match;

	while (match = regexp.exec(string)) {
		var item = match[1];

		if (item.length && item[0] == '"') {
			item = item.slice(1, -1);
		}

		item = item.replace(/\\\\/g, '\\').replace(/\\"/g, '"');

		array.push(item);
	}

	return array;
}

function pgArrayEncode(array) {
	if (typeof(array) == "string") {
		return array;
	}

	if (!array || !Util.isArray(array)) {
		return "{}";
	}

	for (var i = 0, count = array.length; i < count; i++) {
		var item = array[i];

		item = '"' + item.replace(/\\/g, "\\\\").replace(/"/g, '\\"') + '"';
		array[i] = item;
	}

	return "{" + array.join(",") + "}";
}

function check(key, mode) {
	return application.security.check(key, mode);
}

function isRoot() {
	return application.security.isRoot();
}

function appSettings(settings) {
	settings = Util.object(settings);

	if (typeof(settings.xhr) == "object") {
		if (settings.xhr.prefix !== undefined) {
			Xhr.prefix = settings.xhr.prefix;
		}

		if (settings.xhr.header) {
			Xhr.appendAjaxPrefilter();
		}
	}

	if (typeof(settings.module) == "object") {
		if (settings.module.prefix != undefined) {
			Module.prefix = settings.module.prefix;
		}
	}
}

function context(value) {
	try {
		var context = application.context || application.security.context;

		if (value) {
			return Util.Object.value(context, value);
		}

		return context;
	}
	catch (e) {
		console.log(e);
		return null;
	}
}

function widget(className, options) {
	return application.ui.workspace.showWidget(className, options);
}
