Module.define(

	function () {
		NS("City.UI");

		City.UI.AnimateForm = Class({

			initCSS: function (options) {

				if (options && options.animate) {

					//@container
					this.$element.css({
						opacity: 0
					});
				}

			},

			show: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.initCSS(options);

					//@container
					this.$element.transition({
						opacity: 1,
					}, 350, function () {

						this.CallMethod(parentMethod, options);

						if (Util.isFunction(callback)) {
							callback();
						}

					}.bind(this));

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}

				}
			},

			hide: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					//@container
					this.$element.transition({
						opacity: 0
					}, 250, function () {

						this.CallMethod(parentMethod, options);

						if (Util.isFunction(callback)) {
							callback();
						}

					}.bind(this));

				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}

				}
			}

		});
	}
);