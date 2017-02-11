Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.DealerBrands",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.DealerBrands = Class(Base.Form, {
			template: "KrujevaDict/Dealers/DealerBrands",

			fields : {
				id: 'id',
				dealerregionid: 'dealerregionid',
				brandid: 'brandid'
			},

			rules: {
				edit: {
				}
			}

		});
	});