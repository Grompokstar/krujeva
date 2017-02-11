Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Franchisees");

		City.UI.Franchisees.PhonesEditForm = Class(Base.Form, {
			template: "City/Franchisees/PhonesEditForm",

			fields: {
				id: 'id',
				phone: 'Номер телефона'
			},

			rules: {
				edit: {
					phone: ['require']
				}
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('phone').mask('+7 (999) 999-99-99');
			}
		});
	});