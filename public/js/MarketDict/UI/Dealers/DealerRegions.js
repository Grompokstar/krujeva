Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.DealerBrands",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.DealerRegions = Class(Base.Form, {
			template: "KrujevaDict/Dealers/DealerRegions",

			fields : {
				id: 'id',
				dealerid: 'dealerid',
				areaid: 'Регион',
				deliveryday: 'Доставка (в днях)',
				minsum: 'Мин. сумма заказа',

				dealerbrandsselect: 'Бренды'
			},

			blocks: {
				dealerbrands: "KrujevaDict.UI.Dealers.DealerBrands",
			},

			rules: {
				edit: {
					areaid: ['require'],
					deliveryday: ['require', 'integerOnly', 'max=2'],
					minsum: ['require', 'integerOnly']
				}
			},

			validate: function () {
				var valid = this.ParentCall();

				if (!this.dealerbrands) {
					valid = false;
					this.setError('dealerbrands', 'Укажите бренды');
				}

				if (this.dealerbrands && !this.dealerbrands.length) {
					valid = false;
					this.setError('dealerbrands', 'Укажите бренды');
				}

				return valid;
			},
		});
	});