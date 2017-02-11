Module.define(
	"Base.InfinityScroll",

	function () {

	NS("KrujevaMobile");

	KrujevaMobile.FormValidate = Class({

		fields: {},

		itemvalue: {},

		onInputFocus: function (caller) {
			var name = $(caller).attr('name');

			$(caller).parent().removeClass('check').removeClass('error');
		},

		onInputBlur: function (caller) {
			var name = $(caller).attr('name');

			var val = $(caller).val();

			if (!val) {
				return;
			}

			if (!this.validateField(name, val)) {

				this.elementClassable($(caller)).addClass('error');

			} else {

				this.elementClassable($(caller)).addClass('check');
			}
		},

		elementClassable: function (element) {
			return element.parent();
		},

		validate: function () {
			var validate = true;

			for (var i in this.fields) if (this.fields.hasOwnProperty(i)) {

				if (!this.validateField(i, this.itemvalue[i], true)) {

					validate = false;
				}

			}

			return validate;
		},

		validateField: function (name, val, finnaly) {

			var valid = true;

			if (finnaly) {

				var options = this.fields[name];

				if (Util.isArray(options)) {

					for (var i in options) if (options.hasOwnProperty(i)) {

						var option = options[i];

						switch (option) {

							case 'require':

								if (!val) {

									valid = false;

									this.showError(name);
								} else {
									//this.hideError(name);
									//this.showOk(name);
								}
								break;

						}

					}

				}
			}

			return valid;
		},


		showOk: function (name) {

			var $field = this.$name(name);

			if ($field) {

				this.elementClassable($field).addClass('check');
			}
		},

		showError: function (name) {
			var $field = this.$name(name);

			if ($field) {

				this.elementClassable($field).addClass('error');
			}
		},

		hideError: function (name) {
			var $field = this.$name(name);

			if ($field) {

				this.elementClassable($field).removeClass('error');
			}
		},

		getRecord: function () {
			var item = {};

			for (var i in this.fields) if (this.fields.hasOwnProperty(i)) {

				var $field = this.$name(i);

				if ($field) {

					item[i] = $field.val() || null;

					this.itemvalue[i] = $field.val() || null;
				}
			}

			return item;
		},

		setRecord: function (data) {
			var item = {};

			for (var i in this.fields) if (this.fields.hasOwnProperty(i)) {

				var $field = this.$name(i);

				if ($field && data[i]) {

					$field.val(data[i]);

					this.itemvalue[i] = data[i];
				}
			}

			return item;
		}

	});


});