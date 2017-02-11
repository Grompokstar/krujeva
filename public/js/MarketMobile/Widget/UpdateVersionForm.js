Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.Widget");

		KrujevaMobile.Widget.UpdateVersionForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/Widget/UpdateVersionForm",

			initCSS: function (options) {

				if (options && options.animate) {

					var style = [];

					style.push('-moz-transform: translate3d(0, 100%, 0);');
					style.push('-webkit-transform: translate3d(0, 100%, 0);');
					style.push('transform: translate3d(0, 100%, 0);');

					this.$element.attr('style', style.join(''));
				}
			},

			show: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.initCSS(options);

					var style = [];

					style.push('-moz-transform: translate3d(0, 0, 0);');
					style.push('-webkit-transform: translate3d(0, 0, 0);');
					style.push('transform: translate3d(0, 0, 0);');

					setTimeout(function() {

						this.$element.attr('style', style.join(''));

						this.$element.one($.support.transition.end, function () {

							this.CallMethod(parentMethod, options);

							if (Util.isFunction(callback)) {

								this.listenScroll();

								callback();
							}

						}.bind(this));

					}.bind(this), 0);

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}

				}
			},

			destroy: function (options, callback) {
				var parentMethod = this.ParentMethod();

				this.hide(options, function () {

					this.CallMethod(parentMethod);

					if (Util.isFunction(callback)) {
						callback();
					}

				}.bind(this));
			},

			hide: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					var style = [];

					style.push('-moz-transform: translate3d(0, 100%, 0);');
					style.push('-webkit-transform: translate3d(0, 100%, 0);');
					style.push('transform: translate3d(0, 100%, 0);');

					setTimeout(function () {

						this.$element.attr('style', style.join(''));

						this.$element.one($.support.transition.end, function () {

							this.CallMethod(parentMethod, options);

							if (Util.isFunction(callback)) {

								callback();
							}

						}.bind(this));

					}.bind(this), 0);

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}
				}
			},

			hideClick: function () {
				this.destroy({animate: true});
			},

			updateClick: function () {
				this.destroy({animate: true});
				KrujevaMobile.MobileUpdate.updateApp();
			}
		});
	}
);