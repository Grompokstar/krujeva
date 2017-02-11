Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.LoaderForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/LoaderForm",

			object: null,
			method: null,
			options: {},

			initialize: function (parent, options) {
				this.ParentCall();

				if (options && options.object) {
					this.object = options.object;
				}

				if (options && options.method) {
					this.method = options.method;
				}

				if (options && options.options) {
					this.options = options.options;
				}
			},

			destroy: function () {
				this.object = null;
				this.method = null;
				this.options = {};

				this.ParentCall();
			},

			isAnimate: function () {
				var animate = true;

				if (this.options && typeof this.options.animate !== 'undefined') {

					animate = this.options.animate;
				}

				return animate;
			},

			initCSS: function () {

				if (this.isAnimate()) {

					var style = [];

					style.push('transition: all .4s ease;');

					style.push('-webkit-transition: all .4s ease;');

					style.push('-webkit-transform: scale(0.5);');

					style.push('transform: scale(0.5);');

					style.push('opacity: 0;');

					this.$element.attr('style', style.join(''));

				} else {

					this.$element.hide();
				}
			},

			show: function () {

				if (this.isAnimate()) {

					var style = [];

					style.push('transition: all .4s ease;');

					style.push('-webkit-transition: all .4s ease;');

					style.push('-webkit-transform: scale(1);');

					style.push('transform: scale(1);');

					style.push('opacity: 1;');

					setTimeout(function () {

						this.$element.attr('style', style.join(''));

						setTimeout(function () {

							this.callMethod();

						}.bind(this), 200);

					}.bind(this), 0);

				} else {

					this.callMethod();

					var showTimeout = 500;

					if (typeof this.options.showTimeout !== 'undefined') {
						showTimeout = this.options.showTimeout;
					}

					setTimeout(function () {

						if (this.$element) {

							this.$element.show();
						}

					}.bind(this), showTimeout);
				}
			},

			callMethod: function () {

				var loader = Loader.start($([]), this.options);

				var self = this;

				this.method.call(this.object, function (xhrData, xhrResult, callback) {

					Loader.end(loader, function () {

						//ERR_INTERNET_DISCONNECTED
						if (xhrData == null && xhrResult && xhrResult.errorCode == 106) {

							var showNoInternet = true;

							//если нам сказали не показывать интернет
							if (typeof self.options.showNoInternet !== 'undefined') {
								showNoInternet = self.options.showNoInternet;
							}

							if (showNoInternet) {

								self.showNoInternet();

							} else {

								self.closeClick();
							}

							return false;
						}

						self.closeClick();

						callback(xhrData, xhrResult);
					});

				});
			},

			closeClick: function () {

				if (!this.$element) {
					return;
				}

				if (this.isAnimate()) {

					var style = [];

					style.push('transition: all .4s ease;');

					style.push('-webkit-transition: all .4s ease;');

					style.push('-webkit-transform: scale(.5);');

					style.push('transform: scale(.5);');

					style.push('opacity: 0;');

					setTimeout(function () {

						if (!this.$element) {
							return;
						}

						this.$element.attr('style', style.join(''));

					}.bind(this), 0);

					setTimeout(function () {

						this.destroy();

					}.bind(this), 600);


				} else {

					this.destroy();
				}
			},

			repeatClick: function () {

				this.showLoader();

				this.callMethod();
			},

			showNoInternet: function () {

				this.$name('loader').hide();

				this.$name('no-internet').show();
			},

			showLoader: function () {

				this.$name('loader').show();

				this.$name('no-internet').hide();
			}

		});
	}
);