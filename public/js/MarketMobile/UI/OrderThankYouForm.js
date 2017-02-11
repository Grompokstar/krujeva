Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.OrderThankYouForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/OrderThankYouForm",

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function (page, options) {
				LocalStorage.set('history.pages', ['provider']);

				//@dealer region id
				var dealerregionid = application.orderData['order']['dealer']['dealerregionid'];

				application.cartForm.clearStorageDataByDealerRegionId(dealerregionid);

				application.orderData = {};
			},

			thankClick: function () {
				application.open('provider', {animate: true, backClick: true});
			}
		});
	}
);