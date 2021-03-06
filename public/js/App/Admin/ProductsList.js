Module.define(

	function () {
		NS("App.Admin");

		App.Admin.ProductsList = Class(App.BaseForm, {

			initialize: function () {
				this.$container = $('body');

				this.initInteractionEvents();
			},

			removeClick: function (caller) {

				if (!confirm('Удалить?')) {
					return;
				}

				var id = $(caller).data('id');

				Xhr.call('/Products/remove',{
					id: id
				}, function (result, response) {

					result = result.data;

					if (result && result.item) {

						Alert.success('Успешно удалено');

						setTimeout(function () {
							window.location = '/Admin/products';
						}, 500);
						 
					} else {

						Alert.error('Не удалось сохранить');
					}

				}.bind(this));
			}

		});
	});
