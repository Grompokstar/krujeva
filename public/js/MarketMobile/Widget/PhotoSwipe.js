Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.Widget");

		KrujevaMobile.Widget.PhotoSwipe = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/Widget/PhotoSwipe",

			options: {},

			gallery: null,

			initialize: function (parent, options) {
				this.ParentCall();

				application.pageSwitcher.unlistenPanel();

				if (options && options.options) {
					this.options = options.options;
				}
			},

			destroy: function () {
				this.options = {};

				this.gallery = null;

				application.pageSwitcher.listenPanel();

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

					style.push('transition: all .2s ease;');

					style.push('-webkit-transition: all .2s ease;');

					style.push('-webkit-transform: scale(0.02);');

					style.push('transform: scale(0.02);');

					style.push('opacity: 0;');

					this.$element.attr('style', style.join(''));

				} else {

					this.$element.hide();
				}
			},

			show: function () {

				this.openPhotoSwipe();

				if (this.isAnimate()) {

					var style = [];

					style.push('transition: all .2s ease;');

					style.push('-webkit-transition: all .2s ease;');

					style.push('-webkit-transform: scale(1);');

					style.push('transform: scale(1);');

					style.push('opacity: 1;');

					this.$element.css('opacity', 0).removeClass('init-gallery');

					setTimeout(function () {

						if (this.$element) {
							this.$element.attr('style', style.join(''));
						}

					}.bind(this), 100);

				}
			},

			openPhotoSwipe: function () {

				if (!this.options) {
					return;
				}

				if (!this.options.path) {
					return;
				}

				var pswpElement = this.$element.get(0);

				var items = [
					{
						src: this.options.path,
						w: this.options.width,
						h: this.options.height
					}
				];

				var options = {
					history: false,
					focus: false,

					showAnimationDuration: 0,
					hideAnimationDuration: 0,
					maxSpreadZoom: 4,
					pinchToClose: false
				};

				this.gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);

				this.gallery.init();

				this.gallery.listen('destroy', function () {
					this.destroy();
				}.bind(this));
			},

			closeClick: function () {

				if (!this.$element) {
					return;
				}

				var style = [];

				style.push('transition: all .3s ease;');

				style.push('-webkit-transition: all .3s ease;');

				style.push('-webkit-transform: scale(.7);');

				style.push('transform: scale(.7);');

				style.push('opacity: 0;');

				setTimeout(function () {

					if (!this.$element) {
						return;
					}

					this.$element.attr('style', style.join(''));

				}.bind(this), 0);

				setTimeout(function () {

					if (this.gallery) {
						this.gallery.destroy();
					}

					this.destroy();

				}.bind(this), 600);
			},



		});
	}
);