Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.FormValidate",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.AreasForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/AreasForm",

			regionindex: {},

			destroy: function () {

				this.regionindex = {};

				this.ParentCall();
			},

			afterRender: function () {

				this.listenScroll();

				var keys = Object.keys(Regions);

				for (var i = 0, l = keys.length; i < l; i++) {

					var region = Regions[keys[i]];

					this.regionindex[region['id']] = region;
				}
			},

			backClick: function () {
				application.backOpenPage();
			},

			onKeyUpSearch: function (el) {

				var val = $(el).val().toLowerCase();

				var newItems = [];

				var limit = 30;

				var indexItem = 0;

				//@areas
				var keys = Object.keys(Regions);

				for (var i = 0, l = keys.length; i < l; i++) {

					var region = Regions[keys[i]];

					var name = region.name.toLowerCase();

					if (indexItem < limit && ~name.indexOf(val)) {

						newItems.push(region);

						indexItem++;
					}
				}

				if (val) {

					var keys = Object.keys(Cities);

					for (var i = 0, l = keys.length; i < l; i++) {
						var region = Cities[keys[i]];

						var name = region.name.toLowerCase();

						if (indexItem < limit && ~name.indexOf(val)) {

							newItems.push(region);

							indexItem++;
						}
					}
				}

				newItems = Util.sort(newItems, 'name');

				var $html = $([]);

				var keys = Object.keys(newItems);

				for (var i = 0, l = keys.length; i < l; i++) {

					var item = newItems[keys[i]];

					var $el = UI.Template.render("KrujevaMobile/Areas/ItemForm", {item: item, widget: this});

					$html = $html.add($el);
				}

				//@insert
				this.$name('card-cnt').empty();

				$html.appendTo(this.$name('card-cnt'));

				this.initInteractionEvents();

				this.refreshIscroll();
			},

			regionName: function (id) {
				var region = this.regionindex[id];

				if (!region) {
					return '';
				}

				return region['name'];
			},

			regionClick: function (caller) {

				var id = $(caller).data('id');

				var region = this.regionindex[id];

				if (region) {

					application.open('cities', {animate: true, region: region});

					return;
				}


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