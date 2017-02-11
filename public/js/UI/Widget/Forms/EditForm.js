Module.define(
	"UI.Widget.Form",
	"UI.Widget.Text",
	"UI.Plugin.DataAutocomplete",

	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.EditForm = Class(UI.Widget.Form, {
			formTemplate: "UI/Widget/Forms/EditForm/EditForm",
			columnTemplates: "UI/Widget/Forms/EditForm/Column/",

			draggable: false,

			columns: [],

			item: null,

			dataAutocompletes: {},
			datepickers: {},

			initialize: function (parent, options) {
				var self = this;

				this.override(["columns", "item"], options);

				this.Parent(parent, options);

				if (!Util.isObject(this.item)) {
					this.item = {};
				}
			},

			render: function () {
				var self = this;

				Util.each(this.dataAutocompletes, function (autocomplete, name) {
					autocomplete.destroy();

					delete self.dataAutocompletes[name];
				});

				Util.each(this.datepickers, function (element, name) {
					element.datepicker("destroy");

					delete self.datepickers[name];
				});

				this.ParentCall();

				Util.each(this.columns, function (column) {
					switch (column.type) {
						case "DataAutocomplete":
							var source = new column.source();

							var autocomplete = new UI.Widget.Text(self, {
								element: self.$("[data-column='" + column.name + "']")
							});

							autocomplete.use(UI.Plugin.DataAutocomplete, {
								source: source,
								columns: column.columns,
								column: column.column,
								sourceOptions: column.sourceOptions,
								strict: column.strict
							}).on("select", function (args) {
								if (Util.isFunction(column.onSelect)) {
									column.onSelect.call(self, args.item);
								}
							});

							self.dataAutocompletes[column.name] = autocomplete;
							break;
						case "DateTimePicker":
						case "DatePicker":
						case "TimePicker":
							var pickerMethod = column.type.toLowerCase();

							var $element = self.$("[data-column='" + column.name + "']");
							var options = column.datepickerOptions ? Util.clone(column.datepickerOptions) : {};
							var value = self.item[column.name] === undefined ? "" : self.item[column.name];

							if (column.type == "DateTimePicker") {
								options["dateFormat"] = "dd.mm.yy";
								options["timeFormat"] = "HH:MM";
								options["fieldTimeOnly"] = false;
							}

							if (column.type == "DatePicker") {
								options["dateFormat"] = "dd.mm.yy";
							}

							if (column.type == "TimePicker") {
								options["fieldTimeOnly"] = true;
							}

							$element[pickerMethod](options);

							if (value) {
								var date;
								
								if (column.type == "DateTimePicker") {
									date = Date.fromTZ(value);
								}

								if (column.type == "DatePicker") {
									date = Date.fromDate(value);
								}

								if (column.type == "TimePicker") {
									date = Date.fromTimeTZ(value);
								}

								$element.datepicker('setDate', date);
							}

							break;
						case "PGArray":
							var htmlOptions = column.htmlOptions ? Util.clone(column.htmlOptions) : {};

							htmlOptions["data-column"] = column.name;
							htmlOptions["on-change"] = "onPgArrayColumnChange";

							var htmlOptionsString = [];

							Util.each(htmlOptions, function (value, key) {
								htmlOptionsString.push(key + '="' + Html.encode(value) + '"');
							});

							htmlOptionsString = htmlOptionsString.join(" ");

						function createRow (key, value) {
							var $input;
							var $row = $('<div class="row"></div>');

							switch (column.input) {
								case "Callback":
									if (Util.isFunction(column.callback)) {
										$input = column.callback(key, value);
									}
									break;
								case "TextArea":
									$input = $('<textarea ' + htmlOptionsString + '>' + Html.encode(value) + '</textarea>');
									break;
								default:
									$input = $('<input type="text" value="' + Html.encode(value) + '" ' + htmlOptionsString + ' />');
									break;
							}

							$row.append($input);

							var $button = $('<span class="icon-remove" title="Удалить" data-key="' + key + '" style="position: absolute; margin-top: 10px; margin-left: -20px"></span>').on("click", function () {
								rows[key].remove();
								rows.splice(key, 1);
								self.pgArrayColumnReload(column.name);
							});

							$row.append($button);

							return $row;
						}

							var $container = self.$("[data-pg-array-column-container='" + column.name + "']");
							var rows = [];

							var values = self.item[column.name] ? pgArrayDecode(self.item[column.name]) : [];

							Util.each(values, function (value, key) {
								rows.push(createRow(key, value));
							});

							$container.find(".rows").empty().append(rows);

							var $button = $('<span class="icon-plus" title="Добавить"></span>').on("click", function () {
								var row = createRow(rows.length, '');
								rows.push(row);
								$container.find(".rows").append(row);
							});

							$container.find(".buttons").append($button);

							break;
					}
				});

				return this;
			},

			columnContent: function (column) {
				if (!column.type) {
					column.type = "Text";
				}

				return UI.Template.render(this.columnTemplates + column.type, { widget: this, column: column });
			},

			setColumnValue: function (name, value, caller) {
				this.item[name] = value;
			},

			onSaveClick: function () {
				this.emit("save", { item: this.item });
			},

			onSaveCloseClick: function () {
				this.emit("save", { item: this.item, close: true });
			},

			onIntColumnChange: function (caller) {
				var name = $(caller).data("column");

				this.setColumnValue(name, +$(caller).val(), caller);
			},

			onTextColumnChange: function (caller) {
				var name = $(caller).data("column");

				this.setColumnValue(name, $(caller).val(), caller);
			},

			onEnumColumnChange: function (caller) {
				var name = $(caller).data("column");
				var value = $(caller).val();

				this.setColumnValue(name, value.length ? +value : null, caller);
			},

			onCheckboxColumnClick: function (caller) {
				var name = $(caller).data("column");
				var value = $(caller).is(":checked") ? 1 : 0;

				this.setColumnValue(name, value, caller);
			},

			onPGEnumChecksColumnClick: function (caller) {
				var name = $(caller).data("column");
				var value = +$(caller).val();
				var array = pgIntArrayDecode(this.item[name]);

				if ($(caller).is(":checked")) {
					if (!~array.indexOf(value)) {
						array.push(value);
					}
				} else {
					var index = array.indexOf(value);

					if (~index) {
						array.splice(index, 1);
					}
				}

				this.item[name] = pgIntArrayEncode(array);
			},

			onPgArrayColumnChange: function (caller) {
				var name = $(caller).data("column");
				this.pgArrayColumnReload(name);
			},

			onDatePickerColumnChange: function (caller) {
				var $element = $(caller);
				var name = $element.data("column");
				var type = $element.data("type");
				var date = $element.datepicker("getDate");

				var value = "";

				if (date) {
					if (type == "DateTimePicker") {
						value = date.format("isoDateTime")
					}

					if (type == "DatePicker") {
						value = date.format("isoDate")
					}

					if (type == "TimePicker") {
						value = date.format("isoTime")
					}
				}

				this.setColumnValue(name, value, caller);
			},

			pgArrayColumnReload: function (name) {
				var $container = this.$("[data-pg-array-column-container='" + name + "']");

				var array = [];
				$container.find("[data-column='" + name + "']").each(function () {
					var value = String($(this).val()).trim();
					if (value.length) {
						array.push(value);
					}
				});

				this.item[name] = pgArrayEncode(array);
			}
		});
	}
);
