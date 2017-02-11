Module.define(
	"Base.Form",
	"City.UI.Franchisees.PhonesEditForm",

	function () {
		NS("City.UI.Franchisees");

		City.UI.Franchisees.OwnerEditForm = Class(Base.Form, {
			template: "City/Franchisees/OwnerEditForm",

			fields: {
				id: 'id',
				name: 'Имя',
				surname: 'Фамилия',
				patronymic: 'Отчество',
				bithdate: 'День рождения',
				userid: 'userid',

				relativepath: 'relativepath',
				photoname: 'photoname'
			},

			rules: {
				edit: {
					name: ['require'],
					surname: ['require'],
				}
			},

			blocks: {
				phones: "City.UI.Franchisees.PhonesEditForm"
			},

			render: function (options) {

				if (options && options.item) {
					this.setRecord(options.item);
				}

				if (!this.phones) {
					this.addBlock('phones', {}, []);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				var days = 100 * 365 * -1;

				this.fieldElement('bithdate').datepicker({
					changeMonth: true,
					changeYear: true,
					minDate: days,
					maxDate: "+0D"
				});
			},

			addPhoneClick: function () {
				this.addBlock('phones', {}, [], {render: true});
			}
		});
	});