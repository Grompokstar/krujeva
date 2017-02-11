Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.OrderAddressForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/OrderAddressForm",

			fields: {
				'city': ['require'],
				'address': ['require'],
				'salonname': ['require']
			},

			afterRender: function () {
				this.listenScroll();

				this.setRenderAddress();
			},

			 afterOpen: function (page, options) {

				this.setRenderAddress();
			},

			setRenderAddress: function () {

				if (application.orderData['barbershop']) {

					var cityid = application.orderData['barbershop']['cityid'];

					var city = application.findCity(cityid);

					if (city) {

						this.setRecord({city: city['name']});
					}
				}
			},

			backClick: function () {
				application.backOpenPage();
			},

			cityClick: function () {

				var region = application.findRegion(context('regionid'));

				application.open('ordercity', {region: region, animate: true});
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {
					return;
				}

				record['text'] = record['city'] + ', ' + record['address'] + ', ' + record['salonname'];

				record['cityid'] = application.orderData['barbershop']['cityid'];

				application.orderData['barbershop'] = Util.clone(record);

				//history clear
				this.clearHistoryPages(['orderaddress']);

				application.open('order', {addressChange: true, animate: true, backClick: true});
			},

			selectAddressClick: function (caller) {
				var index = $(caller).data('index');

				var barbershops = context('barbershops') || [];

				var barbershop = barbershops[index];

				if (!barbershop) {
					return;
				}

				var city = application.findCity(barbershop['cityid']);

				application.orderData['barbershop'] = {
					text: (city ? city['name'] : '') + ', ' + barbershop['address'] + ', ' + barbershop['salonname'],
					cityid: (city ? city['id'] : ''),
					address: barbershop['address'],
					id: barbershop['id']
				};

				//history clear
				this.clearHistoryPages(['orderaddress']);

				application.open('order', {addressChange: true, animate: true, backClick: true});
			},
		});
	}
);