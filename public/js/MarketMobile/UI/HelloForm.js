Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.HelloForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/HelloForm",

			swiper: null,

			destroy: function () {

				if (this.swiper) {
					this.swiper.destroy();
					this.swiper = null;
				}

				this.ParentCall();
			},

			afterOpen: function () {

				if (this.swiper) {
					return;
				}

				this.swiper = new Swiper(this.$element, {
					pagination: this.$('.swiper-pagination'),
					paginationClickable: true,

					onSlideNextStart: function () {

						if (this.swiper.isEnd) {
							this.$name('hide-last-page').hide();

							LocalStorage.set('page.hello.visited', 'ok');
						}


					}.bind(this),

					onSlidePrevStart: function () {

						this.$name('hide-last-page').show();

					}.bind(this)
				});
			},

			nextClick: function () {
				this.swiper.slideNext(function () {}, 500);
			},

			authClick: function () {
				application.open('auth1', {animate: true});
			},

			registrationClick: function () {
				application.open('auth3', {animate: true});
			}
		});
	}
);