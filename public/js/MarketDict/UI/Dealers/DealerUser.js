Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.DealerBrands",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.DealerUser = Class(Base.Form, {
			template: "KrujevaDict/Dealers/DealerUser",

			fields : {
				id: 'id',
				login: 'Мобильный телефон'
			},

			rules: {
				edit: {
					login: ['require']
				}
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('login').mask('+7 (999) 999-99-99');
			}

		});
	});