Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.ServerSourceCheckbox = Class(UI.Widget.Forms.Control.Element, {
			source: null,
			loading: false,
			callbacks: [],

			createElement: function () {
				var self = this;
				var attributes = {};
				var onClick = this.$control.attr("on-click");
				var sourceClass;
				var valueColumn = this.$control.attr("col-value");
				var textColumn = this.$control.attr("col-text");
				var multiline = this.$control.attr("multiline");

				if (multiline == "no") {
					multiline = false;
				}

				multiline = !!Util.coalesce(multiline, true);

				if (onClick) {
					attributes["on-click"] = onClick;
				}

				eval("sourceClass = " + this.$control.attr("source") + ";");

				this.source = new sourceClass();

				this.loading = true;

				this.source.find(null, function (args) {
					var items = args ? args.items : [];

					self.$element = UI.Template.$render("UI/Widget/Forms/Control/ServerSourceCheckbox/Control", {
						items: items,
						valueColumn: valueColumn,
						textColumn: textColumn,
						attributes: attributes,
						multiline: multiline
					});

					self.loaded();
				});
			},

			insertElement: function () {
				this.defer(function () {
					var self = this;

					this.$element.insertAfter(this.$control);

					$("input", this.$element).on("click", function () {
						var value = [];

						$("input", self.$element).each(function () {
							if ($(this).is(":checked")) {
								value.push(this.value);
							}
						});

						self.setValue(value);
					});
				});
			},

			updateElement: function (value) {
				this.defer(function () {
					if (!Util.isArray(value)) {
						value = [];
					}

					$("input", this.$element).each(function () {
						$(this).prop("checked", !!~value.indexOf(this.value));
					});
				});
			},

			applyAttributes: function () {
				var method = this.ParentMethod();

				this.defer(function () {
					method.call(this);
				});
			},

			defer: function (callback) {
				if (this.loading) {
					this.callbacks.push(callback);
				} else {
					callback.call(this);
				}
			},

			loaded: function () {
				var callback;

				this.loading = false;

				while (callback = this.callbacks.shift()) {
					callback.call(this);
				}
			}
		});
	}
);
