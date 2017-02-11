Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.Widget.SearchForm",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.BrandCategoryForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/BrandCategoryForm",

			categories: [],

			oldcategories: [],

			pagedata: {},

			searchvalue: null,

			searchForm: null,


			//@request
			openedPage: false, //открылась ли страница? анимация законилась ?

			executeRequestCallback: false, //выполнена ли callback функция

			requestData: null, //храним данные ответа

			requestResult: null, //храним данные ответа

            isRequestEnd: false, //закончилось выполнение запроса или нет


            initialize: function () {
                this.ParentCall();

				this.searchForm = new KrujevaMobile.Widget.SearchForm(this);

				this.searchForm.on('data', this.onSearchData, this);

				this.searchForm.on('clear', this.onSearchClear, this);

				this.searchForm.on('clearlist', this.onSearchClearList, this);

                application.cartForm.on('changed', this.onCartChanged, this);
            },

			destroy: function () {
                application.cartForm.off('changed', this.onCartChanged, this);

				if (this.searchForm) {

					this.searchForm.off('data', this.onSearchData, this);

					this.searchForm.off('clear', this.onSearchClear, this);

					this.searchForm.off('clearlist', this.onSearchClearList, this);

					this.searchForm.destroy();

					this.searchForm = null;
				}

				this.categories = [];

				this.pagedata = {};

				this.ParentCall();
			},

            onCartChanged: function (args) {
                application.cartForm.updateCountElement(this.$name('svgbasket'), args.data, this.pagedata.dealerregionid);
            },

			render: function (options) {

				if (options && options.data) {

					this.pagedata = Util.clone(options.data);
				}

				if (options && options.categories) {

					this.categories = options.categories;
				}

				//old categories render
				/*if (this.pagedata && this.pagedata.brandid) {

					var old = LocalStorage.get('brand.category.items.' + this.pagedata.brandid);

					if (old) {

						this.oldcategories = old;
					}
				}*/

				this.ParentCall();

				if (!this.categories.length) {

					application.showLoader(this, this.serverSend, {
						text: '&nbsp;',
						bgcolor: 'transparent',
						bgloader: '#0288D1',
						bgloadercircle: 'rgba(2, 136, 209, .25)',
						prependElement: this.$element,
						animate: false,
						showTimeout: 0,
						loadertimeout: 0
					});
				}

				//@render search widget
				this.searchForm.setPageData(this.pagedata, this.$name('list-items'));
				this.searchForm.render().$element.appendTo(this.$name('search-form'));

				return this;
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function () {

				this.openedPage = true;

				this.callbackRequestAfterOpen();
			},

			backClick: function () {
				application.backOpenPage();
			},

			cartClick: function () {
				application.open('cart', {animate: true});
			},

			serverSend: function (callback) {

				//this.pagedata = {brandid: 4};

				if (!this.pagedata || !this.pagedata.brandid) {
					callback(null, null, function (data, result) {});
					return;
				}

				var source = new KrujevaMobile.Data.ProductCategories();

				var self = this;

				this.executeRequestCallback = false;

                this.isRequestEnd = false;

				source.mobilelist({
					brandid: this.pagedata.brandid,
					version: LocalStorage.get('brand.category.version.' + self.pagedata.brandid)
				}, function (data, result) {

					callback(data, result, function (data, result) {

						self.requestData = data;

						self.requestResult = result;

                        self.isRequestEnd = true;

						self.callbackRequestAfterOpen();

					});

				});
			},

			callbackRequestAfterOpen: function () {

                if (!this.isRequestEnd) {
                    return;
                }

				if (!this.openedPage) {
					return;
				}

				if (this.executeRequestCallback) {
					return;
				}

				this.executeRequestCallback = true;

				var data = this.requestData;

				var result = this.requestResult;


				if (data) {

					//@save version
					if (data.version) {
						LocalStorage.set('brand.category.version.' + this.pagedata.brandid, data.version);
					}

					//@save items
					if (data.items) {

						LocalStorage.set('brand.category.items.' + this.pagedata.brandid, data.items);

						this.categories = data.items;
					}

					//@right version
					if (data == 'right version') {

						this.categories = LocalStorage.get('brand.category.items.' + this.pagedata.brandid);

					}

					this.render();

				} else {

					if (result && result.message) {

						Alert.error(result.message);

					} else {

						Alert.error('Не удалось получить список категорий');
					}

				}

			},

			categoryClick: function (caller) {

				var id = $(caller).data('id');

				var categories = this.categories;

				var keys = Object.keys(categories);

				var category = null;

				for (var i = 0, l = keys.length; i < l; i++) {

					var item = categories[keys[i]];

					if (item.id == id) {

						category = Util.clone(item);

						break;
					}
				}

				if (!category) {
					return;
				}

				//@childrens
				if (category['childrens'] && Util.isArray(category['childrens']) && category['childrens'].length) {

					var currentCategoryNode = 1;

					if (!this.pagedata.currentCategoryNode) {
						this.pagedata.currentCategoryNode = currentCategoryNode;
					}

					this.pagedata.currentCategoryNode++;

					this.pagedata['categoryname'] = category['name'];

					application.open('brandcategory'+ this.pagedata.currentCategoryNode, {
						animate: true,
						data: Util.clone(this.pagedata),
						categories: category['childrens'].slice()
					});

					return;
				}

				this.pagedata['categoryid'] = category['id'];

				this.pagedata['categoryname'] = category['name'];

				application.open('listproducts', {
					animate: true,
					data: Util.clone(this.pagedata)
				});
			},




			//@search widget
			onSearchData: function ($html) {

				this.$name('offset-list').empty();

				$html.appendTo(this.$name('offset-list'));

				this.refreshIscroll(true);
			},

			onSearchClear: function () {

				//@render return data
				var html = UI.Template.render("KrujevaMobile/BrandCategory/ListForm", {widget: this});

				this.$name('offset-list').html(html);

				this.initInteractionEvents();

				this.refreshIscroll();

				this.searchForm.initInteractionEvents();
			},

			onSearchClearList: function (data) {

				this.$name('offset-list').empty();
			},



		});
	}
);