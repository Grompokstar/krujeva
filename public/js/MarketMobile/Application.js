Module.define(
	"KrujevaMobile.Page",
	"UI.Renderer.EJS",
	"UI.Template",
	"Security.Module",
	"System.NumSeq",
	"KrujevaMobile.PageSwitcher",
	"KrujevaMobile.Widget.CartForm",
	"KrujevaMobile.MobileUpdate",

	"KrujevaMobile.UI.WelcomeForm",
	"KrujevaMobile.UI.ProviderForm",
	"KrujevaMobile.UI.MenuForm",
	"KrujevaMobile.UI.Auth1Form",
	"KrujevaMobile.UI.Auth2Form",
	"KrujevaMobile.UI.SettingForm",
	"KrujevaMobile.UI.Auth3Form",
	"KrujevaMobile.UI.Auth4Form",
	"KrujevaMobile.UI.AreasForm",
	"KrujevaMobile.UI.CitiesForm",
	"KrujevaMobile.UI.LoaderForm",
	"KrujevaMobile.UI.PasswordVerifyForm",
	"KrujevaMobile.UI.SuccessRegistrationForm",
	"KrujevaMobile.UI.ForgotForm",
	"KrujevaMobile.UI.BrandCategoryForm",
	"KrujevaMobile.UI.ListProductsForm",
	"KrujevaMobile.UI.ProductForm",
	"KrujevaMobile.UI.CartForm",
	"KrujevaMobile.UI.OrderFioForm",
	"KrujevaMobile.UI.OrderAddressForm",
	"KrujevaMobile.UI.OrderForm",
	"KrujevaMobile.UI.OrderCityForm",
	"KrujevaMobile.UI.HelloForm",
	"KrujevaMobile.UI.OrderThankYouForm",
	"KrujevaMobile.UI.DealerForm",
	"KrujevaMobile.UI.BonusForm",
	"KrujevaMobile.AnimationSync",
	"KrujevaMobile.UI.BonusCategoryForm",
	"KrujevaMobile.UI.ListBonusProductsForm",
	"KrujevaMobile.UI.BonusProductForm",
	"KrujevaMobile.UI.UpdateAppForm",
	"KrujevaMobile.UI.HistoryForm",
	"KrujevaMobile.UI.HistoryItemForm",

	function () {
		NS("KrujevaMobile");

		KrujevaMobile.Application = Class(KrujevaMobile.Page, {

			MOBILE_VERSION: null,

			hashLevel: -1,

			//ширина рабочего пространства приложения
			mainWidth: 0,

			//высота рабочего пространства приложения
			mainHeight: 0,

			//контекст авторизованного пользователя
			context: null,

			//класс для отрисовки меню
			menuForm: null,

			//базовый лоадер - для запросов на сервер - и смотрит что нет инета
			loaderForm: null,

			//клас для переключения страниц - свайпом
			pageSwitcher: null,

			//класс для работы с корзиной
			cartForm: null,

			//собираем данные которые при регистрации вводятся
			registrationData: {},

			//данные при оформлении корзины
			orderData: {},

			pages: {
				hello: ["KrujevaMobile.UI.HelloForm"],

				//welcomepage
				welcome: ["KrujevaMobile.UI.WelcomeForm", {destroyOnHide: true}],

				//auth
				auth1: ["KrujevaMobile.UI.Auth1Form"],
				forgot: ["KrujevaMobile.UI.ForgotForm"],

				//registration
				auth3: ["KrujevaMobile.UI.Auth3Form"],
				auth4: ["KrujevaMobile.UI.Auth4Form"],
				areas: ["KrujevaMobile.UI.AreasForm"],
				cities: ["KrujevaMobile.UI.CitiesForm"],
				passwordverify: ["KrujevaMobile.UI.PasswordVerifyForm"],
				successreg: ["KrujevaMobile.UI.SuccessRegistrationForm"],

				setting: ["KrujevaMobile.UI.SettingForm"],

				//дилеры
				provider: ["KrujevaMobile.UI.ProviderForm"],

				//карточка дилера
				dealer: ["KrujevaMobile.UI.DealerForm"],

				//категории бренда
				brandcategory1: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory2: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory3: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory4: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory5: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory6: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory7: ["KrujevaMobile.UI.BrandCategoryForm"],
				brandcategory8: ["KrujevaMobile.UI.BrandCategoryForm"],

				//список товаров
				listproducts: ["KrujevaMobile.UI.ListProductsForm"],

				//карточка товара
				product: ["KrujevaMobile.UI.ProductForm"],

				//корзина товаров
				cart: ["KrujevaMobile.UI.CartForm"],

				//оформление заявки фио
				order: ["KrujevaMobile.UI.OrderForm"],
				orderaddress: ["KrujevaMobile.UI.OrderAddressForm"],
				ordercity: ["KrujevaMobile.UI.OrderCityForm"],
				orderthankyou: ["KrujevaMobile.UI.OrderThankYouForm"],

				//бонусы
				bonus: ["KrujevaMobile.UI.BonusForm"],

				//категории бонусов
				bonuscategory1: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory2: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory3: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory4: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory5: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory6: ["KrujevaMobile.UI.BonusCategoryForm"],
				bonuscategory7: ["KrujevaMobile.UI.BonusCategoryForm"],

				//список товаров
				listbonusproducts: ["KrujevaMobile.UI.ListBonusProductsForm"],

				//карточка товара
				bonusproduct: ["KrujevaMobile.UI.BonusProductForm"],

				//обновление приложения
				updateapp: ["KrujevaMobile.UI.UpdateAppForm"],

				//история заявок
				history: ["KrujevaMobile.UI.HistoryForm"],

				historyitem: ["KrujevaMobile.UI.HistoryItemForm"],
			},

			defaultPage: "provider",

			initialize: function () {
				this.ParentCall();

				//Xhr.prefix = 'http://62.109.4.208';
				Xhr.prefix = 'http://mobile.kvik-club.ru';
				this.MOBILE_VERSION = 1;

				UI.Template.renderer = UI.Renderer.EJS;

				this.security = new Security.Security(new Security.Context());

				var self = this;

				this.onResizeHandler = function () {
					self.onWindowResize();
				};

				$(window).on('resize', this.onResizeHandler);

				this.pageSwitcher = new KrujevaMobile.PageSwitcher();

				this.cartForm = new KrujevaMobile.Widget.CartForm();

                //@ios body class
                if (Util.isIOS()) {
                    $('body').addClass('ios-body');
                }

                document.addEventListener('backbutton', onBackKeyDown, false);

                function onBackKeyDown(event) {
                    // Handle the back button
                    event.preventDefault();
                    //application.hidePages();
                    application.backOpenPage()
                }
			},

			destroy: function () {
				$(window).off('resize', this.onResizeHandler);

				this.loaderForm = null;

				this.menuForm = null;

				this.context = null;

				if (this.pageSwitcher) {

					this.pageSwitcher.destroy();

					this.pageSwitcher = null;
				}

				if (this.cartForm) {

					this.cartForm.destroy();

					this.cartForm = null;
				}

				this.ParentCall();
			},

			init: function () {
				this.onWindowResize();

				this.preFilter();

				this.showLoader(this, this.aliveSession, {
					textcolor: '#000',
                    bgcolor: 'transparent',
                    bgloader: '#0288D1',
                    bgloadercircle: 'rgba(2, 136, 209, .25)',
					text: 'Загрузка...',
					animate: false,
					loadertimeout: 0,
					showTimeout: 0
				});
			},

			$page: function () {
				return $('#main-container');
			},

			onWindowResize: function () {
				var $el = this.$page();

				this.mainWidth = $el.outerWidth();
				this.mainHeight = $el.outerHeight();
			},

			getMainWidth: function () {
				return this.mainWidth;
			},

			showMenu: function (options) {
				this.menuForm.showMenu(this.$page(), this.getMainWidth(), options);
			},


			//что делать после того как открылась страница
			afterOpen: function (page, options) {

				options = Util.object(options);

				var backMoveMode = options.backMoveMode ? options.backMoveMode : false;

				//1 чистим историю
				var clearHistoryPage = ['welcome', 'provider', 'setting', 'bonus', 'orderthankyou', 'updateapp', 'history'];

				if (!backMoveMode && clearHistoryPage.indexOf(page) !== -1) {
					LocalStorage.set('history.pages', [page]);
				}
				// end 1


				//2 открываем меню везде кроме
				var notShowMenuPage = ['hello', 'forgot', 'welcome', 'auth1', 'auth2', 'auth3', 'auth4', 'areas', 'cities', 'passwordverify', 'successreg'];

				if (!this.menuForm && !backMoveMode && notShowMenuPage.indexOf(page) == -1) {

					this.menuForm = this.showWidget("KrujevaMobile.UI.MenuForm", {
						prependElement: $('body')
					});

				} else if (this.menuForm && !backMoveMode && notShowMenuPage.indexOf(page) != -1) {

					this.menuForm.destroy();

					this.menuForm = null;
				}
				// end 2


				//4 закрываем остальные страницы логина если открыли provider
				var closeIfOpen = {
					'welcome': true, //закрыть все нахер
					'provider': true, //закрыть все нахер
					'setting': true, //закрыть все нахер
					'bonus': true, //закрыть все нахер
					'orderthankyou': true, //закрыть все нахер
					'updateapp': true, //закрыть все нахер
					'history': true, //закрыть все нахер
					'auth4': ['areas', 'cities']
				};

				if (!backMoveMode && closeIfOpen[page]) {

					var list = closeIfOpen[page];

					if (list === true) {

						for (var i in this.openedPages) if (this.openedPages.hasOwnProperty(i)) {

							if (i == page) {
								continue;
							}

							this.openedPages[i].destroy();
						}


					} else {

						for (var j in list) if (list.hasOwnProperty(j)) {

							var pageName = list[j];

							if (!this.openedPages[pageName]) {
								continue;
							}

							this.openedPages[pageName].destroy();
						}
					}
				}
				// end 4



				//5 корзина которая внизу = показать или скрыть
				var openedCardPages = [
					'brandcategory1',
					'brandcategory2',
					'brandcategory3',
					'brandcategory4',
					'brandcategory5',
					'brandcategory6',
					'listproducts',
					'product',
					'bonuscategory1',
					'bonuscategory2',
					'bonuscategory3',
					'bonuscategory4',
					'bonuscategory5',
					'listbonusproducts',
					'bonusproduct'
				];

				if (!backMoveMode && openedCardPages.indexOf(page) != -1) {

					this.cartForm.show(this.openedPages[page].pagedata);

				} else if (!backMoveMode && openedCardPages.indexOf(page) == -1) {

					this.cartForm.hide();
				}
				// end 5


				return this.ParentCall();
			},


			//показать лоадер - и отправить запрос на сервер
			showLoader: function (object, method, options) {

				if (this.loaderForm) {

					this.loaderForm.destroy();

					this.loaderForm = null;
				}

				var prependElement = options && options.prependElement ? options.prependElement : $('body');

				this.loaderForm = this.showWidget("KrujevaMobile.UI.LoaderForm", {
					prependElement: prependElement,
					object: object,
					method: method,
					options: options
				}).on('destroy', function () {

						this.loaderForm = null;

				}.bind(this));
			},

			//сохраним контекст пользователя
			setServerData: function (data) {

				if (data.sid) {
					LocalStorage.set('SID', data.sid);
				}

				//@context
				if (data.context) {
					this.context = Util.clone(data.context);

					//обновилось приложение возможно
					KrujevaMobile.MobileUpdate.init();
				}
			},

			//к каждому запросу добавим ключ для сессии
			preFilter: function () {

				$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
					options.dataType = 'json';
					options.beforeSend = function (jqXHR) {
						jqXHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

						var sid = LocalStorage.get('SID');

						if (sid) {
							jqXHR.setRequestHeader('SID', sid);
						}
					};
				});
			},

			//пристарте приложения проверяем жива ли сессия
			aliveSession: function (callback) {

				var source = new KrujevaMobile.Data.Users();

				var self = this;

				source.alivemobile({}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							self.setServerData({context: data});
						}

						self.openNextPage();
					});

				});
			},

			openNextPage: function (options) {

				var page = this.nextPage();

				this.open(page, options);
			},

			nextPage: function () {
				var page = this.ParentCall();

				if (!context('phone')) {

					//@hello page
					if (!LocalStorage.get('page.hello.visited')) {
						return 'hello';
					}

					return 'welcome';

				} else {

					if (page == 'welcome' || page == 'hello') {

						page = this.defaultPage;
					}

					return page;
				}
			},

			findCity: function (id) {

				var keys = Object.keys(Cities);

				var item = null;

				for (var i = 0, l = keys.length; i < l; i++) {

					var city = Cities[keys[i]];

					if (city['id'] == id) {
						item = city;
						break;
					}
				}

				return item;
			},

			findRegion: function (id) {
				var keys = Object.keys(Regions);

				var item = null;

				for (var i = 0, l = keys.length; i < l; i++) {

					var city = Regions[keys[i]];

					if (city['id'] == id) {
						item = city;
						break;
					}
				}

				return item;
			}
		});
	}
);