Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.HistoryForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/HistoryForm",

			history: [],

			nohistory: false,

			//@request
			openedPage: false, //открылась ли страница? анимация законилась ?

			executeRequestCallback: false, //выполнена ли callback функция

			requestData: null, //храним данные ответа

			requestResult: null, //храним данные ответа

			isRequestEnd: false, //закончилось выполнение запроса или нет

			afterRender: function () {
				this.listenScroll();
			},

			menuClick: function () {
				application.showMenu();
			},

			render: function (options) {

				this.ParentCall();

				if (!this.history.length && !this.nohistory) {

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


				return this;
			},

			afterOpen: function () {

				this.openedPage = true;

				this.callbackRequestAfterOpen();
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Orders();

				var self = this;

				source.mobilelisthistory({userid: context('userid')}, function (data, result) {

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


				if (data && Util.isArray(data) && data.length) {

					this.history = data;

					this.render();

				} else {

					if (result && result.message) {

						Alert.error(result.message);

					} else {

						//нет брендов в регионе
						this.nohistory = true;

						this.render();

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
						return 'Дата доставки '+ item['localdeliverydate'];
						break;
					case 3:
						return 'Отменена';
						break;
				}

				return '';
			},

			itemClick: function (caller) {

				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				//historyitem
				application.open('historyitem', {animate: true, id: id});
			}

		});
	}
);