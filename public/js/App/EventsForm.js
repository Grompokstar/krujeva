Module.define(

	function () {
		NS("App");

		App.EventsForm = Class({

			initialize: function () {
				this.initSwiper();
			},

			initSwiper: function () {

				var swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					nextButton: '.swiper-button-next',
					prevButton: '.swiper-button-prev',
					slidesPerView: 1,
					paginationClickable: true,
					spaceBetween: 30,
					loop: true,
					autoplay: 4500,
					autoplayDisableOnInteraction: false
				});
			},

		});
	});

