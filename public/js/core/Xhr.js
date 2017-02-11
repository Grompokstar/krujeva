GLOBAL.Xhr = Static({
	prefix: "",

	/**
	 * Constructor.
	 */
	initialize: function () {
		$.ajaxSetup({
			dataType: "text",
			type: "GET",
			async: true,
			timeout: 30000,
			cache: false
		});
	},

	args: function (args, clone) {
		var self = this;

		args = Util.object(args);

		if (clone) {
			args = Util.clone(args);
		}

		Util.each(args, function (value, name) {
			if (value === null) {
				delete args[name];
			} else if (typeof(value) == "object") {
				Xhr.args(value);
			} else if (value === true) {
				args[name] = 1;
			} else if (value === false) {
				args[name] = "";
			}
		});

		return args;
	},

	request: function (options, callback) {
		if (typeof(options) == "string") {
			options = {
				url: options
			};

			options.async = !!callback;
		}

		options = Util.object(options);

		var prefix = Util.coalesce(options.prefix, this.prefix, "");

		if (options.unprefix) {
			prefix = '';
		}

		if (!options.url) {
			return false;
		}

		options.url = prefix + options.url;

		options.cache = false;

		var result = true;

		options.success = function (data) {

			//unavtorized
			if (data && data['401']) {

				Base.Hash.setHash('/');

				window.location.reload(true);

				return;
			}

			if (Util.isFunction(callback)) {
				callback(data);
			}

			if (options.async === false) {
				result = data;
			}
		};

		options.error = function (jqXHR, error, errorThrown) {

			if (jqXHR.status == 0) {
				jqXHR['errorCode'] = 106;
			}

			if (Util.isFunction(callback)) {
				callback(null, error, jqXHR);
			}

			if (options.async === false) {
				result = null;
			}
		};

		var ajaxResult = $.ajax(options);

		if (options.async !== false) {
			result = ajaxResult;
		}

		return result;
	},

	upload: function (options, callback, uploadcallback) {
		if (!options || typeof(options) != "object") {
			return false;
		}

		if (!options.files) {

		}

		var form = new FormData();

		if (options.files) {

			Util.each(options.files, function(file){
				form.append(file.name, file.file.files[0]);
			});

		} else if (options.file) {
			form.append(options.filename, options.file.files[0]);
		}

		if (options.data && typeof(options.data) == "object") {
			Util.each(options.data, function (value, name) {
				form.append(name, value);
			});
		}

		options.type = "POST";
		options.dataType = "json";
		options.data = form;
		options.contentType = false;
		options.processData = false;

		var requestCallback = null;

		if (Util.isFunction(uploadcallback)) {
			options.xhr = function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function (evt) {
					if (!evt.lengthComputable) {
						return;
					}

					uploadcallback(parseInt((evt.loaded / evt.total) * 100));
				}, false);
				return xhr;
			};
		}

		if (Util.isFunction(callback)) {
			requestCallback = function (data, error, jqXHR) {
				if (error) {
					callback({
						success: false,
						data: null,
						message: "Непреодолимая ошибка",
						code: -1,
						errorCode: jqXHR.errorCode
					});
				} else {
					callback(data);
				}
			};
		}

		return this.request(options, requestCallback);
	},

	call: function (url, args, callback, options) {
		if (Util.isFunction(args)) {
			callback = args;
			args = null;
		}

		options = Util.object(options);

		args = Util.object(args);

		var methodType = args.methodType ? args.methodType : 'POST';

		delete  args.methodType;

		options = Util.merge(options, {
			url: url,
			async: !!callback,
			type: methodType,
			dataType: "json",
			data: args
		}, true);



		var requestCallback = null;

		if (Util.isFunction(callback)) {
			requestCallback = function (data, error, jqXHR) {
				if (error) {
					callback({
						success: false,
						data: null,
						message: "Непреодолимая ошибка",
						code: -1,
						errorCode: jqXHR.errorCode
					});
				} else {
					callback(data);
				}
			};
		}

		return this.request(options, requestCallback);
	},

	doCall: function (url, params, done, callback) {
		if (callback) {
			this.call(url, params, function (result) {
				var ret = done(result);

				if (Util.isFunction(callback)) {
					callback(ret);
				}
			});

			return null;
		} else {
			var result = this.call(url, params);
			return done(result);
		}
	},

	doUpload: function (options, done, callback) {
		if (callback) {
			this.upload(options, function (result) {
				var ret = done(result);

				if (Util.isFunction(callback)) {
					callback(ret);
				}
			});

			return null;
		} else {
			var result = this.upload(options);
			return done(result);
		}
	},

	appendAjaxPrefilter: function () {
		$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
			options.beforeSend = function (jqXHR) {
				jqXHR.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			};
		});
	},

	appendDataPrefilter: function (callback, context) {
		$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
			var data = originalOptions.data;

			if (typeof(data) == "string") {
				data = $.deparam(originalOptions.data);
			} else if (!data || typeof(data) != "object") {
				data = {};
			}

			Util.call(callback, context, [data]);

			options.data = $.param(data);
		});
	},

	alert: function (message, response) {
		if (response && Util.isObject(response)) {
			if (!response.success && Util.isDefined(response.code) && Util.isDefined(response.message)) {
				message += "\n\nКод: " + response.code + "\nСообщение: " + response.message;
			}
		}

		alert(message);
	}
});
