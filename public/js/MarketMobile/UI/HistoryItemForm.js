Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.HistoryItemForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/HistoryItemForm",

			id: null,

			item: null,

			notitem: false,

			//@request
			openedPage: false, //открылась ли страница? анимация законилась ?

			executeRequestCallback: false, //выполнена ли callback функция

			requestData: null, //храним данные ответа

			requestResult: null, //храним данные ответа

			isRequestEnd: false, //закончилось выполнение запроса или нет


			destroy: function () {
				this.item = null;

				this.ParentCall();
			},

			render: function (options) {

				if (options && options.id) {

					this.id = options.id;
				}

				this.ParentCall();

				if (!this.item) {

					application.showLoader(this, this.serverSend, {
						text: '&nbsp;',
						bgcolor: 'transparent',
						bgloader: '#0288D1',
						bgloadercircle: 'rgba(2, 136, 209, .25)',
						prependElement: this.$element,
						animate: false,
						loadertimeout: 0
					});

				}

				return this;
			},

			afterRender: function () {
				this.listenScroll();
			},

			backClick: function () {
				application.backOpenPage();
			},

			afterOpen: function () {

				this.openedPage = true;

				this.callbackRequestAfterOpen();
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Orders();

				var self = this;

				source.mobileitem({orderid: this.id}, function (data, result) {

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


					var item = data.item;

					for (var i in item.orderproducts) if (item.orderproducts.hasOwnProperty(i)) {

						var product = item.orderproducts[i];


						for (var x in data.products) if (data.products.hasOwnProperty(x)) {

							var pr = data.products[x];

							if (pr['id'] == product['productid']) {

								item.orderproducts[i] = Util.merge(item.orderproducts[i], pr);

								break;
							}

						}

					}

					console.log(item);

					this.item = item;

					this.render();

				} else {

					if (result && result.message) {

						Alert.error(result.message);

					} else {

						//нет брендов в регионе
						this.notitem = true;

					//	this.render();

						//Alert.error('Не удалось получить список поставщиков');
					}

				}

			},

			getStatusClass: function (item) {

				if (!item) {
					return '';
				}

				switch (+item.status) {
					case 1:
						return 'new';
						break;
					case 2:
						return 'confirmed';
						break;
					case 3:
						return 'canceled';
						break;
				}

				return '';
			},

			getStatusText: function (item) {
				if (!item) {
					return '';
				}

				switch (+item.status) {
					case 1:
						return 'Ожидает подтверждения от поставщика';
						break;
					case 2:
						return 'Дата доставки ' + item['localdeliverydate'];
						break;
					case 3:
						return 'Отменена';
						break;
				}

				return '';
			},

			getImgSrc: function (item) {
				return Xhr.prefix + item['photopath'] + (Util.isRetina() ? '2x_' : '') + item['photoname'];
			},

		});
	}
);