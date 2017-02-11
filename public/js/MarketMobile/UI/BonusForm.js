Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.BonusForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/BonusForm",

			menuClick: function () {
				application.showMenu();
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function () {

				var source = new KrujevaMobile.Data.Users();

				var self = this;

				source.mobilebonus({userid: context('userid')}, function (data) {

					if (data) {

						application.context.bonus = data;

						self.$name('bonus-value').html(data);

						self.$name('bonus-label').html(Util.declOfNum(data, [' балл', ' балла', ' баллов']));
					}

				});
			},

			bonusCatalogClick: function () {

				var data = {
					brandid: 91234847,
					dealerbrandid: 92347823,
					dealername: 'Призы',
					brandname: 'Каталог призов',
					minsum: 10,
					dealerregionid: 23492347,
					bonuscart: true
				};

				application.cartForm.restoreCart(data);

				application.open('bonuscategory1', {animate: true, data: data});
			}

		});
	}
);