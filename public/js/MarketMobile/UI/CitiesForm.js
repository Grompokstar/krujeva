Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.CitiesForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/CitiesForm",

			region: {},
			cities: [],

			destroy: function () {
				this.region = {};

				this.cities = [];

				this.ParentCall();
			},

			afterRender: function () {
				this.listenScroll();
			},

			render: function (options) {

				if (options && options.region) {

					this.region = Util.clone(options.region);

					this.formCities();
				}

				return this.ParentCall();
			},

			formCities: function () {

				var ids = {};

				for (var j in this.region['childrens']) if (this.region['childrens'].hasOwnProperty(j)) {

					ids[this.region['childrens'][j]] = true;
				}

				var keys = Object.keys(Cities);

				for (var i = 0, l = keys.length; i < l; i++) {

					var city = Cities[keys[i]];

					if (ids[city['id']]) {

						this.cities.push(city);
					}
				}
			},

			backClick: function () {
				application.backOpenPage();
			},

			onKeyUpSearch: function (el) {

				var val = $(el).val().toLowerCase();

				var newItems = [];

				//@areas
				var keys = Object.keys(this.cities);

				for (var i = 0, l = keys.length; i < l; i++) {

					var item = this.cities[keys[i]];

					var name = item.name.toLowerCase();

					if (~name.indexOf(val)) {

						newItems.push(item);
					}
				}

				newItems = Util.sort(newItems, 'name');

				var $html = $([]);

				var keys = Object.keys(newItems);

				for (var i = 0, l = keys.length; i < l; i++) {

					var item = newItems[keys[i]];

					var $el = UI.Template.render("KrujevaMobile/Cities/ItemForm", {item: item});

					$html = $html.add($el);
				}

				//@insert
				this.$name('card-cnt').empty();

				$html.appendTo(this.$name('card-cnt'));

				this.refreshIscroll();

				this.initInteractionEvents();
			},

			regionClick: function (caller) {

				var id = $(caller).data('id');

				//@find city
				var keys = Object.keys(Cities);

				var city = null;

				for (var i = 0, l = keys.length; i < l; i++) {

					var cityItteration = Cities[keys[i]];

					if (cityItteration['id'] == id) {

						city = Util.clone(cityItteration);

						break;
					}
				}

				if (city) {

					var reqData = {
						'city': city['name'],
						'cityid': city['id']
					};

					//history clear
					this.clearHistoryPages(['cities', 'areas', 'auth4']);

					application.registrationData = Util.merge(application.registrationData, reqData, true);

					application.open('auth4', {animate: true, backClick: true});
				}
			},

		});
	}
);