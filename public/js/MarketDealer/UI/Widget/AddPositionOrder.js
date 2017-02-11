Module.define("Base.Form",

	function () {

		NS("KrujevaDealer.UI.Widget");

		KrujevaDealer.UI.Widget.AddPositionOrder = Class(Base.Form, {
			template: "KrujevaDealer/Widget/AddPositionOrder",

			fields: {
				brandid: "Выберите бренд",
				dealerbrandid: "Бренд",

				brandselectid: "Бренд",

				search: "Поиск"
			},

			rules: {
				edit: {
					localdeliverydate: ['require'],
					orderid: ['require']
				}
			},

			order: null,

			searchrequest: null,

			products: {},

			initialize: function (parent, options) {
				this.ParentCall();

				if (options && options.order) {

					this.order = options.order;
				}
			},

			initCSS: function (options) {

				if (options && options.animate) {

					this.$element.css({opacity: 0});

					this.$name('container').css({
						x: 450
					});
				}
			},

			show: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.initCSS(options);

					this.$element.transition({opacity: 1}, 250);

					this.$name('container').transition({
						x: 0
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
			},

			destroy: function (options, callback) {
				var parentMethod = this.ParentMethod();

				this.hide(options, function () {

					this.order = null;
					this.searchrequest = null;
					this.products = {};

					this.CallMethod(parentMethod);

					if (Util.isFunction(callback)) {
						callback();
					}

				}.bind(this));
			},

			hide: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					this.$element.transition({opacity: 0}, 250);

					this.$name('container').transition({
						x: 450
					}, 300, function () {

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

			cntClick: function (caller, ev) {
				ev.preventDefault();
				ev.stopPropagation();
			},

			bgClick: function () {
				this.destroy({animate: true});
			},

			cancelClick: function () {
				this.destroy({animate: true});
			},

			getBrandSelectOptions: function () {

				var brands = application.data.regionbrands;

				var dealerregionid = this.order['dealerregionid'];

				var result = [];

				for (var i in brands) if (brands.hasOwnProperty(i)) {

					var brand = brands[i];

					if (brand['dealerregionid'] != dealerregionid) {
						continue;
					}

					result.push({
						id: brand['dealerbrandid'],
						brandid: brand['brandid'],
						dealerbrandid: brand['dealerbrandid'],
						name: brand['brandname']
					});
				}

				if (result.length == 1) {

					var brandR = result[0];

					this.setValue('brandid', brandR['brandid'], true);

					this.setValue('dealerbrandid', brandR['dealerbrandid'], true);
				}

				return result;
			},

			onChangeBrand: function () {
				this.setFieldsValue();

				var brandselectid = this.getValue('brandselectid');

				var brands = application.data.regionbrands;

				var dealerregionid = this.order['dealerregionid'];

				var selectedBrand = null;

				for (var i in brands) if (brands.hasOwnProperty(i)) {

					var brand = brands[i];

					if (brand['dealerregionid'] != dealerregionid) {
						continue;
					}

					if (brand['dealerbrandid'] == brandselectid) {
						selectedBrand = brand;
					}
				}

				if (!selectedBrand) {
					return null;
				}

				this.setValue('brandid', selectedBrand['brandid'], true);

				this.setValue('dealerbrandid', selectedBrand['dealerbrandid'], true);

				this.$name('product-add-h-block').show();

				this.$name('search-items').empty();
			},

			onChangeSearch: function (caller) {

				var value = $(caller).val();

				var brandid = this.getValue('brandid');

				var dealerbrandid = this.getValue('dealerbrandid');

				if (!brandid || !dealerbrandid || !value) {

					this.$name('search-items').empty();

					return;
				}

				var self = this;

				this.$name('search-items').empty();

				if (this.searchrequest) {
					this.searchrequest.abort();
					this.searchrequest = null;
				}

				this.searchrequest = Xhr.call('http://mobile.kvik-club.ru/Products/mobilesearch?value='+ value +'&brandid='+ brandid+'&dealerbrandid='+ dealerbrandid, {}, function (data) {

					if (data && data.data && Util.isArray(data.data)) {

						self.renderSearchItems(data.data);
					}
				});
			},

			getBrandName: function () {

				var brandid = this.getValue('brandid');

				var brands = application.data.regionbrands;

				var brandname = '';

				for (var j in brands) if (brands.hasOwnProperty(j)) {

					var brand = brands[j];

					if (brand['brandid'] == brandid) {

						brandname = brand['brandname'];
						break;
					}
				}

				return brandname;
			},

			renderSearchItems: function (items) {

				var brandname = this.getBrandName();

				var $html = $([]);

				this.products = {};

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					item['price'] = 0;

					if (item['priceobject']) {

						var price = JSON.parse(item['priceobject']);

						item['price'] = price['price'];
					}

					this.products[item['id']] = {
						name: item['name'],
						brand: brandname

					};

					$html = $html.add(UI.Template.$render('KrujevaDealer/Orders/SearchItem', {item: item}));
				}

				$html.appendTo(this.$name('search-items'));
			},

			addClick: function (caller) {

				var productid = $(caller).data('productid');

				var price = $(caller).data('price');

				if (!productid || !price) {
					return;
				}

				var item = Util.merge(this.products[productid], {
					productid: productid,
					count: 1,
					price: price
				}, true);

				this.emit('add', {item: item});

				this.destroy({animate: true});
			}

		});
	});