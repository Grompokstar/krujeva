Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.OrderForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/OrderForm",

			fields: {
				'surname': ['require'],
				'name': ['require'],
				'phone': ['require'],
				'comment': []
			},

			elementClassable: function (element) {
				return element.parent().parent().parent();
			},

			initialize: function () {
				this.ParentCall();

				this.getOrderAddress();
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function (page, options) {

				if (options.addressChange) {

					var text = application.orderData['barbershop']['text'];

					this.$name('address-text').html(text);
				}

				var record = this.getRecord();

				var datarecord  = {};

				if (!record['phone'] && context('phone')) {
					datarecord['phone'] = context('phone');
				}

				if (context('name')) {
					datarecord['name'] = context('name');
				}

				if (context('surname')) {
					datarecord['surname'] = context('surname');
				}

				this.setRecord(datarecord);
			},

			backClick: function () {
				application.backOpenPage();
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {
					return;
				}

				application.orderData['fio'] = Util.clone(record);

				application.open('orderaddress', {animate: true});
			},

			getOrderAddress: function () {

				var barbershops = context('barbershops') || [];

				if (typeof barbershops[0] == 'undefined') {

					application.orderData['barbershop'] = {
						text: null,
						cityid: null,
						address: null,
						id: null
					};

					return;
				}

				var lastbarbershop = null;
				var indexshop = null;
				var maxdatetime = null;

				for (var i in barbershops) if (barbershops.hasOwnProperty(i)) {

					var item = barbershops[i];

					//var x = DateTime.getDate(item['lastorderdatetime'], 'X', 'YYYY-MM-DD HH:mm:ss');
					var x = item['lastorderdatetime'];

					if (maxdatetime < x || !maxdatetime) {
						maxdatetime = x;
						indexshop = i;
					}
				}

				var barbershop = barbershops[indexshop];

				var city = application.findCity(barbershop['cityid']);

				application.orderData['barbershop'] = {
					text: (city ? city['name'] : '') + ', '+ barbershop['address'] + ', '+ barbershop['salonname'],
					cityid: (city ? city['id'] : ''),
					address: barbershop['address'],
					id: barbershop['id']
				};
			},

			changeAddressClick: function () {
				application.open('orderaddress', {animate: true});
			},

			orderClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {

					return;
				}

				application.orderData['client'] = Util.clone(record);

				application.showLoader(this, this.serverSend, {
					text: 'Отправляем заказ...',
					bgcolor: 'rgba(255,255,255, .9)',
					bgloader: '#0288D1',
					bgloadercircle: 'rgba(2, 136, 209, .25)',
					prependElement: this.$element,
					animate: false,
					showTimeout: 0
				});

			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Orders();

				if (application.orderData['order']['dealer']['bonuscart']) {

					source = new KrujevaMobile.Data.BonusOrders();
				}

				source.addorder(application.orderData, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							application.setServerData({context: data});

							application.open('orderthankyou', {animate: true});

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

							}

						}

					});

				});
			},

		});
	}
);