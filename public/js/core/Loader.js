Module.define(

	function () {

		GLOBAL.Loader = Static({
			path: "",
			timeout: 600,
            showTimeout: 250,
			index: 0,

			timers: {},

			start: function ($loader, options) {
				this.index++;

				options = Util.object(options);

				var timeout = this.timeout;

				if (typeof options.loadertimeout !== 'undefined') {

					timeout = parseInt(options.loadertimeout);
				}

                var showTimeout = this.showTimeout;

                if (typeof options.showTimeout !== 'undefined') {

                    showTimeout = parseInt(options.showTimeout);
                }

				this.timers[this.index] = {
					starttimestamp: new Date().getTime(),
					indextimeout: null,
					loader: $loader,
					timeout: timeout,
                    showTimeout: showTimeout
				};

                (function (loader, showTimeout, index, context) {

                    setTimeout(function () {

                        var timer = context.timers[index];

                        if (!timer) {
                            return;
                        }

                        var timestamp = new Date().getTime();

                        if (timestamp - timer.starttimestamp > timer.timeout) {
                            return;
                        }

                        if (loader) {

                            loader.show();
                        }

                    }.bind(context), showTimeout);

                })($loader, showTimeout, this.index, this);

				return this.index;
			},

			end: function (index, callback, endForce) {
				var timer = this.timers[index];

				if (!timer) {

					if (Util.isFunction(callback)) {
						callback();
					}

					return;
				}

                if (endForce) {

                    timer.loader.hide();

                    delete this.timers[index];

                    if (Util.isFunction(callback)) {
                        callback();
                    }

                    return;
                }

				var timestamp = new Date().getTime();

				if (timestamp - timer.starttimestamp > timer.timeout) {

					timer.loader.hide();

					if (Util.isFunction(callback)) {
						callback();
					}

					return;
				}

				timer.indextimeout = setTimeout(function () {
					delete this.timers[index];

					timer.loader.hide();

					if (Util.isFunction(callback)) {
						callback();
					}

				}.bind(this), timer.timeout - (timestamp - timer.starttimestamp));
			},

			customHtml: function (template, visible, options, args) {
				visible = typeof visible == 'undefined' ? true : visible;
				options = options || {};
				args = args || {};

				if (options.style) {
					options.style += ' display:' + (visible ? 'block;' : 'none;');
				} else {
					options.style = 'display:' + (visible ? 'block;' : 'none;');
				}

				return UI.Template.render(template, {htmlOptions: this.getHtmlOptions(options), args: args});
			},

			html: function (visible, options) {
				visible = typeof visible == 'undefined' ? true : visible;
				options = options || {};

				if (options.style) {
					options.style += ' display:' + (visible ? 'block;' : 'none;');
				} else {
					options.style = 'display:' + (visible ? 'block;' : 'none;');
				}

				if (options.class) {
					options.class += ' absolute-loader';
				} else {
					options.class = 'absolute-loader';
				}

				if (options.absoluteLoader) {
					delete options.absoluteLoader;

					return '<div ' + this.getHtmlOptions(options) + '><div class="x-loader line-scale-pulse-out"><div></div><div></div><div></div><div></div><div></div></div></div>'
				}

				return '<div class="x-loader line-scale-pulse-out" ' + this.getHtmlOptions(options) + '><div></div><div></div><div></div><div></div><div></div></div>'
			},

			getHtmlOptions: function (htmlOptions) {
				htmlOptions = htmlOptions || {};
				var options = [];

				for (var keyOption in htmlOptions) if (htmlOptions.hasOwnProperty(keyOption)) {
					options.push(keyOption + '="' + htmlOptions[keyOption] + '"');
				}

				return options.join(' ');
			}
		});
	}
);
