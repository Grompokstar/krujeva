jQuery.fn.childNodes = function () {
	return this.pushStack(jQuery.map(this, "jQuery.makeArray(a.childNodes)"));
};

Module.define(
	"DateTime",
	"UI.Renderer.EJS",
	"UI.Template",
	"App.BaseForm",
	"App.MainForm",
	"App.EventsForm",
	"App.Admin.PageLogin",
	"App.Admin.NewsAdd",
	"App.Admin.NewsList",
	"App.Admin.EventsAdd",
	"App.Admin.EventsList",
	"App.Admin.ProductsAdd",
	"App.Admin.ProductsList",
	"App.Admin.SliderAdd",
	"App.Admin.SliderList",
	"App.Admin.SliderEventsAdd",
	"App.Admin.SliderEventsList",
	"App.Admin.Index",
	"App.ContactsForm",
	"App.Admin.FeedbackForm",

	function () {
		NS("App");

		App.Application = Class({

			config: {
				'/': 'App.MainForm',
				'/events': 'App.EventsForm',
				'/admin/pagelogin': 'App.Admin.PageLogin',
				'/Admin/newsadd': 'App.Admin.NewsAdd',
				'/Admin/news': 'App.Admin.NewsList',
				'/Admin/eventsadd': 'App.Admin.EventsAdd',
				'/Admin/events': 'App.Admin.EventsList',
				'/Admin/productsadd': 'App.Admin.ProductsAdd',
				'/Admin/products': 'App.Admin.ProductsList',
				'/Admin/slideradd': 'App.Admin.SliderAdd',
				'/Admin/slider': 'App.Admin.SliderList',
				'/Admin/slidereventsadd': 'App.Admin.SliderEventsAdd',
				'/Admin/sliderevents': 'App.Admin.SliderEventsList',
				'/admin': 'App.Admin.Index',
				'/Admin': 'App.Admin.Index',
				'/Admin/feedback': 'App.Admin.FeedbackForm',
				'/contacts': 'App.ContactsForm',
			},

			initialize: function () {

				UI.Template.renderer = UI.Renderer.EJS;

				var className = this.getClass();

				if (className) {

					setTimeout(function () {

						Util.create(className);

					}, 0);
				}

				this.afterInit();
			},

			getClass: function () {

				var path = location.pathname;

				if (!this.config[path]) {
					return null;
				}

				return this.config[path];
			},

			afterInit: function () {

				if (typeof ymaps !== 'indefined') {

					setTimeout(function () {

						ymaps.ready(this.initMap.bind(this));

					}.bind(this), 500);
				}
			},

			initMap: function () {

				var controls = ["zoomControl"];

				if (typeof isMobilePage !== 'undefined') {
					controls = [];
				}

				if (!$('#map').length) {
					return;
				}

				var myMap = new ymaps.Map("map", {
					center: [55.788398, 49.172027],
					zoom: 16,
					controls: controls
				});

				myMap.behaviors.disable('scrollZoom');

				var myPlacemark = new ymaps.Placemark([55.788398, 49.172027], {
					hintContent: 'Ресторан кружева',
					balloonContent: 'Ресторан Кружева <br/> ул. Гвардейская, 15, 1 этаж <br/> +7 (843) 272-18-34'
				});

				myMap.geoObjects.add(myPlacemark);
			}

		});

		$(function () {
			new App.Application();
		});
	});

