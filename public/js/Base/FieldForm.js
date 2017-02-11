Module.define(
	"Base.FormValidate",
	"UI.Widget.Text",
	"OSM.Plugin.DataAutocomplete",
	"Html",
	"Base.Forms.Input",
	"UI.Plugin.ChosenSelect",

	function () {
		NS("Base");

		Base.FieldForm = Class(Base.FormValidate, {
			autoSave: true,

			activeAutocompleteOptions: {},

			activeAutocomplete: {},

			chosenSelectOptions: {},

			autoSaveFields: [],

			activeChosen: {},

			customFields: {},

			$formListContainer: null,

			afterRender: function () {
				this.ParentCall();

				this.bindAutocomplete();
				this.bindAutoSave();
				this.initCustomForms();

				this.bindChosenSelect();

				return this;
			},

			destroy: function () {
				this.unbindAutocomple();
				this.destroyCustomForms();

				this.unbindChosenSelect();

				this.$formListContainer = null;

				this.ParentCall();
			},

			bindChosenSelect: function () {
				this.unbindChosenSelect();

				Util.each(this.chosenSelectOptions, function (options, field) {

					this.bindChosenSelectField(field);

				}.bind(this));
			},

			unbindChosenSelect: function () {

				Util.each(this.activeChosen, function (chosen, field) {

					chosen.destroy();

					delete this.activeChosen[field];

				}.bind(this));
			},

			bindChosenSelectField: function (field) {

				if (this.activeChosen[field]) {

					var $element = this.$name(this.getFieldUniqueId(field));

					var $clone = $element.clone();

					$element.after($clone);

					this.activeChosen[field].destroy();

					delete this.activeChosen[field];
				}

				var options = this.chosenSelectOptions[field];

				this.activeChosen[field] = new UI.Widget.Text(this, {element: this.$name(this.getFieldUniqueId(field))});

				this.activeChosen[field].use(UI.Plugin.ChosenSelect, Util.merge({
					value: this.getValue(field),
					fieldClass: this
				}, options));
			},

			initCustomForms: function () {

				this.$formListContainer = this.getFormListContainer();

				for (var field in this.customFields) if (this.customFields.hasOwnProperty(field)) {
					var customClass = this.customFields[field];

					var $element = this.$name(field, true);

					if ($element) {

						customClass.initEvents($element, this.$formListContainer);
					}
				}

			},

			destroyCustomForms: function () {

				for (var field in this.customFields) if (this.customFields.hasOwnProperty(field)) {

					var customClass = this.customFields[field];

					customClass.destroy();

					delete this.customFields[field];
				}
			},

			getFormListContainer: function () {
				return null;
			},

			bindAutocomplete: function () {
				this.unbindAutocomple();

				Util.each(this.activeAutocompleteOptions, function (options, field) {

					this.bindAutocompleteField(field);

				}.bind(this));
			},

			bindAutoSave: function () {
				var self = this;

				Util.each(this.autoSaveFields, function (field) {

					var $field = this.fieldElement(field);

					if (!$field.length) {
						return;
					}

					$field.on("keyup change", function () {
						var name = self.getFieldKeyByName($(this).attr("name"));
						var value = $(this).val();

						self.setValue(name, value, false);
					});

				}.bind(this));
			},

			bindAutocompleteField: function (field) {

				if (this.activeAutocomplete[field]) {

					var $element = this.$name(this.getFieldUniqueId(field));

					var $clone = $element.clone();

					$element.after($clone);

					this.activeAutocomplete[field].destroy();

					delete this.activeAutocomplete[field];
				}

				var options = this.activeAutocompleteOptions[field];

				this.activeAutocomplete[field] = new UI.Widget.Text(this, {element: this.$name(this.getFieldUniqueId(field))});

				this.activeAutocomplete[field].use(OSM.Plugin.DataAutocomplete, Util.merge({
					strict: true, cssClass: "geo-search"
				}, options)).on("select", function (args) {

					if (options && options.setValue) {

						Util.each(options.setValue, function (formfieldname, itemfieldname) {

							var value = args.item && typeof args.item[itemfieldname] !== "undefined" ? args.item[itemfieldname] : null;

							this.setValue(formfieldname, value, true);

						}.bind(this));

					} else {

						var value = args.item && typeof args.item[options.column] !== "undefined" ? args.item[options.column] : null;

						this.setValue(field, value, true);
					}

				}.bind(this)).on("empty", function () {

					if (options && options.setValue) {

						Util.each(options.setValue, function (formfieldname, itemfieldname) {

							this.setValue(formfieldname, null, true);

						}.bind(this));

					} else {
						this.setValue(field, null, true);
					}

				}.bind(this));
			},

			unbindAutocomple: function () {

				Util.each(this.activeAutocomplete, function (autocomplete, field) {

					autocomplete.destroy();

					delete this.activeAutocomplete[field];

				}.bind(this));
			},

			getAutocomplete: function (field) {

				if (!this.activeAutocomplete[field]) {
					return null;
				}

				return this.activeAutocomplete[field].plugin(OSM.Plugin.DataAutocomplete);
			},

			getChoosenSelect: function (field) {
				if (!this.activeChosen[field]) {
					return null;
				}

				return this.activeChosen[field].plugin(UI.Plugin.ChosenSelect);
			},

			activeLabel: function (field, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.for = htmlOptions.for || this.getFieldUniqueId(field);

				var label = this.fields[field] || "undefined";

				if (htmlOptions.prefix) {
					label += " " + htmlOptions.prefix;
					delete htmlOptions.prefix;
				}

				if (htmlOptions.label) {
					label = htmlOptions.label;
					delete htmlOptions.label;
				}

				return '<label ' + this.getHtmlOptions(this.addErrorCss(field, htmlOptions)) + '>' + label + '</label>';
			},

			saveFieldValueClicked: function (caller) {
				var el = $(caller);
				var key = this.getFieldKeyByName(el.attr("name"));
				this.setValue(key, el.val());
			},

			activeTextField: function (field, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.name = htmlOptions.name || this.getFieldUniqueId(field);
				htmlOptions.id = htmlOptions.id || htmlOptions.name;
				htmlOptions.type = htmlOptions.type || 'text';
				htmlOptions.autocomplete = 'off';

				if (htmlOptions.type == 'checkbox') {
					return this.activeCheckBox(field, htmlOptions);
				}

				if (typeof htmlOptions.value == 'undefined') {

					htmlOptions.value = this.getValue(field);

					if (htmlOptions.value === null) {
						htmlOptions.value = '';
					}
				}

				if (this.autoSave) {
					this.autoSaveFields.push(field);
				}

				return this.activeInputField(htmlOptions.type, field, htmlOptions);
			},

			activeCustomTextField: function (field, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.name = htmlOptions.name || this.getFieldUniqueId(field);
				htmlOptions.id = htmlOptions.id || htmlOptions.name;
				htmlOptions.type = htmlOptions.type || 'text';

				if (typeof htmlOptions.value == 'undefined') {

					htmlOptions.value = this.getValue(field);

					if (htmlOptions.value === null) {
						htmlOptions.value = '';
					}
				}

				if (this.customFields[htmlOptions.name]) {

					this.customFields[htmlOptions.name].destroy();

					delete this.customFields[htmlOptions.name];
				}

				var custom = new Base.Forms.Input();

				this.customFields[htmlOptions.name] = custom;

				return custom.render(this.addErrorCss(field, htmlOptions));
			},

			activeTextareaField: function (field, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.name = htmlOptions.name || this.getFieldUniqueId(field);
				htmlOptions.id = htmlOptions.id || htmlOptions.name;
				htmlOptions.value = htmlOptions.value || this.getValue(field) || '';

				if (this.autoSave) {
					this.autoSaveFields.push(field);
				}

				return this.activeTextarea(field, htmlOptions);
			},

			activeCheckBox: function (field, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.name = htmlOptions.name || this.getFieldUniqueId(field);
				htmlOptions.type = htmlOptions.type || 'checkbox';
				htmlOptions.value = htmlOptions.value || this.getValue(field) || '';

				if (typeof htmlOptions.checked == "undefined" && Html.checked(+this.getValue(field, false))) {
					htmlOptions.checked = "checked";
				} else {
					delete htmlOptions.checked;
				}

				htmlOptions.id = htmlOptions.id || htmlOptions.name;

				return this.activeInputField(htmlOptions.type, field, htmlOptions);
			},

			activeSelect: function (field, options, htmlOptions) {
				htmlOptions = htmlOptions || {};
				htmlOptions.name = htmlOptions.name || this.getFieldUniqueId(field);
				htmlOptions.id = htmlOptions.id || htmlOptions.name;

				if (this.autoSave) {
					this.autoSaveFields.push(field);
				}

				var opts = [];

				if (htmlOptions.addEmpty) {
					delete htmlOptions.addEmpty;
					opts.push('<option></option>');
				}

				var fieldvalue = htmlOptions.fieldvalue ? htmlOptions.fieldvalue : this.getValue(field);

				for (var i in options) if (options.hasOwnProperty(i)) {
					var option = options[i];
					opts.push(this.optionSelect(option.label, option.value, fieldvalue));
				}

				return '<select ' + this.getHtmlOptions(this.addErrorCss(field, htmlOptions)) + '>' + opts.join("") + '</select>';
			},

			optionSelect: function (label, value, fieldvalue) {

				function selected(value, fieldvalue) {

					if (Util.isArray(fieldvalue) && (~fieldvalue.indexOf("" + value) || ~fieldvalue.indexOf(+value))) {
						return 'selected="selected"';
					} else if (value == fieldvalue) {
						return 'selected="selected"';
					}

					return '';
				}

				return '<option value="' + value + '" ' + selected(value, fieldvalue) + '>' + label + '</option>'
			},

			activeAutocompleteField: function (field, autocompleteOptions, htmlOptions) {

				var textField = this.activeTextField(field, htmlOptions);

				this.activeAutocompleteOptions[field] = autocompleteOptions;

				return textField;
			},

			activeChosenSelect: function (field, chosenOptions, htmlOptions) {

				var textField = this.activeSelect(field, [], htmlOptions);

				this.chosenSelectOptions[field] = chosenOptions;

				return textField;
			},

			hideErrors: function (object) {
				object = object || this;

				object.$('label, select, textarea, input, .custom-input').removeClass('error-validate-attribute');
				object.$('.error-summary-validate-attributes').remove();
			},

			/* don't use - private method*/
			activeTextarea: function (field, htmlOptions) {
				var value = htmlOptions.value || '';
				return '<textarea ' + this.getHtmlOptions(this.addErrorCss(field, htmlOptions)) + '>' + value + '</textarea>';
			},

			activeInputField: function (type, field, htmlOptions) {
				htmlOptions.type = type;
				return '<input ' + this.getHtmlOptions(this.addErrorCss(field, htmlOptions)) + ' />';
			},

			addErrorCss: function (field, htmlOptions) {
				if (!this.validateerrors[field]) {
					return htmlOptions;
				}

				if (htmlOptions.class) {
					htmlOptions.class += ' error-validate-attribute';
				} else {
					htmlOptions.class = 'error-validate-attribute';
				}

				return htmlOptions;
			},

			getHtmlOptions: function (htmlOptions) {
				htmlOptions = htmlOptions || {};
				var options = [];

				for (var keyOption in htmlOptions) if (htmlOptions.hasOwnProperty(keyOption)) {
					options.push(keyOption + '="' + htmlOptions[keyOption] + '"');
				}

				return options.join(' ');
			},

			getFieldUniqueId: function (field) {
				return this.name.replace('.', "_") + "_" + field;
			},

			getFieldKeyByName: function (fieldName) {
				var name = this.name.replace('.', "_");
				fieldName = fieldName.replace(name, '');
				fieldName = fieldName.replace('_', '');
				return fieldName;
			},

			fieldElement: function (field) {
				return this.$name(this.getFieldUniqueId(field));
			}
		});

		Base.FieldForm.Static({

			optionSelect: function (label, value, fieldvalue) {

				function selected(value, fieldvalue) {

					if (Util.isArray(fieldvalue) && (~fieldvalue.indexOf("" + value) || ~fieldvalue.indexOf(+value))) {
						return 'selected="selected"';
					} else if (value == fieldvalue) {
						return 'selected="selected"';
					}

					return '';
				}

				return '<option value="' + value + '" ' + selected(value, fieldvalue) + '>' + label + '</option>'
			},

			getHtmlOptions: function (htmlOptions) {
				htmlOptions = htmlOptions || {};
				var options = [];

				for (var keyOption in htmlOptions) if (htmlOptions.hasOwnProperty(keyOption)) {
					options.push(keyOption + '="' + htmlOptions[keyOption] + '"');
				}

				return options.join(' ');
			}
		});

	});