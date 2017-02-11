Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Playgrounds");

		City.UI.Playgrounds.PhonesEditForm = Class(Base.Form, {
			template: "City/Playgrounds/PhonesEditForm",

			fields: {
				id: 'id',
				playgroundid: 'Площадка',
				phone: 'Номер телефона'
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('phone').mask('+7 (999) 999-99-99');
			}

		});
	});