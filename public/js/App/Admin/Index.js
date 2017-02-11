Module.define(

	function () {
		NS("App.Admin");

		App.Admin.Index = Class(App.BaseForm, {


			initialize: function () {
				this.$container = $('body');

				this.initInteractionEvents();
			},

			saveClick: function (caller) {

				var name = $(caller).data('name');

				var value = $(caller).parent().find('textarea').val();

				var record = {
					name: name,
					value: value
				};

				Xhr.call('/Settings/insert', {
					item: JSON.stringify(record)
				}, function (result, response) {

					result = result.data;

					if (result && result.item) {

						Alert.success('Успешно сохранено');

						setTimeout(function () {
							window.location = '/admin';
						}, 500);
						 
					} else {

						Alert.error('Не удалось сохранить');
					}


				}.bind(this));
			},

		});
	});
