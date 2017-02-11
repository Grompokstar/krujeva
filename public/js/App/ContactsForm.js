Module.define(

	function () {
		NS("App");

		App.ContactsForm = Class(App.BaseForm, {

			initialize: function () {
				this.$container = $('body');

				this.$name('phone').mask('+7 (999)-999-99-99');

				this.initInteractionEvents();
			},

			sendClick: function (caller) {

				var record = this.formRecord(this.$name('form'));

				if (!record['text']) {
					Alert.error('Укажите текст сообщения');
					return;
				}

				if (!record['name']) {
					Alert.error('Укажите ваше имя');
					return;
				}

				if (!record['phone']) {
					Alert.error('Укажите телефон');
					return;
				}


				Xhr.call('/Feedback/insert', {
					item: JSON.stringify(record)
				}, function (result, response) {

					result = result.data;

					if (result && result.item) {

						Alert.success('Успешно отправлено');

						this.$name('form').hide();

					} else {

						Alert.error('Не удалось отправить ');
					}

				}.bind(this));
			},

		});
	});

