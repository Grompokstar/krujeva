Module.define("UI.Plugin.Base", function () {
	NS("UI.Plugin");

	UI.Plugin.Autocomplete = Class(UI.Plugin.Base, {
		template: "UI/Plugin/Autocomplete/Autocomplete",
		itemTemplate: "UI/Plugin/Autocomplete/AutocompleteItem",

		/*
		options: {
			source: {function},
			columns: {Array},
			column: {String},
			onSelect: {function}
		}
		 */

		source: null,
		columns: [],
		column: null,
		strict: false,

		input: null,
		div: null,
		list: null,
		actualValue: "",

		items: [],
		current: 0,

		clearTimeout: null,

		initialize: function (element, options) {
			var self = this;

			options = Util.object(options);

			this.Parent(element, options);

			this.source = this.options.source;
			this.columns = this.options.columns;
			this.column = this.options.column;
			this.strict = this.options.strict;

			if (!this.columns) {
				this.columns = [this.column];
			}

			this.input = this.element.$element;

			this.div = UI.Template.$render(this.template).appendTo("body");
			this.list = this.div.find("[name=list]");

			this.read();

			this.keyDownHandler = function (args) {
				self.processKey(args.keyCode);
			};

			this.keyUpHandler = function (args) {
				self.onKeyUp(args);
			};

			this.windowClickHandler = function () {
				self.hide();
			};

			this.blurHandler = function () {
				self.input.val(self.actualValue);
				self.input.trigger("change");
			};

			this.input.on("keydown", this.keyDownHandler);
			this.input.on("keyup", this.keyUpHandler);
			this.element.liveChange().on("livechange", this.onLiveChange, this);

			if (this.strict) {
				this.input.on("blur", this.blurHandler);
			}

			this.div.hide();
		},

		destroy: function () {
			this.hide();

			this.element.off("livechange");
			this.input.off("keydown", this.keyDownHandler);
			this.input.off("keyup", this.keyUpHandler);

			this.unregisterWindowEvent();

			this.div.remove();

			this.emit("destroy");

			this.ParentCall();
		},

		show: function () {
			this.div.show();

			this.align();

			this.registerWindowEvent();
		},

		hide: function () {
			this.div.hide();

			if (this.changeTimeout) {
				clearTimeout(this.changeTimeout);
				this.changeTimeout = null;
			}

			this.unregisterWindowEvent();
		},

		visible: function () {
			return this.div.is(":visible");
		},

		align: function () {
			var offset = this.input.offset();
			var height = this.input.outerHeight();

			var css = {
				top: offset.top + height,
				left: offset.left,
				width: this.input[0].offsetWidth
			};

			this.div.css(css);
		},

		read: function () {
			this.actualValue = this.input.val();
			this.savedValue = this.input.val();
		},

		load: function () {
			var self = this;
			var pattern = this.element.val();

			this.source({ pattern: pattern }, function (data) {
				self.items = data;

				self.update();
			});
		},

		update: function () {
			var self = this;

			this.list.empty();

			for (var i = 0; i < this.items.length; i++) {
				var item = this.items[i];
				var itemDiv = UI.Template.$render(this.itemTemplate, { plugin: this, item: item, index: i }).appendTo(this.list);

				itemDiv.attr("index", i);

				(function (i) {
					itemDiv.on("click", function () {
						self.onItemSelect(i);
					});

					itemDiv.on("mouseover", function () {
						self.autocompleteItemOver(i);
					});
				})(i);
			}

			this.current = 0;
			this.highlight();
		},

		highlight: function () {
			$(".autocomplete__selected", this.div).removeClass("autocomplete__selected");
			$("[index=" + this.current + "]", this.div).addClass("autocomplete__selected");

			this.scrollToSelected();
		},

		scrollToSelected: function () {
			var element = $("[index=" + this.current + "]", this.div);

			if (element.length) {
				var spaceHeight = this.div.outerHeight();
				var height = element.outerHeight();
				var position = element.position();

				if (position.top + height > spaceHeight) {
					element[0].scrollIntoView(false);
				} else if (position.top < 0) {
					element[0].scrollIntoView();
				}
			}
		},

		autocompleteItemOver: function (index) {
			this.current = index;
			this.highlight();
		},

		processKey: function (code) {
			var self = this;
			switch (code) {
				case KeyCode.Tab:
					if (this.visible()) {
						this.onItemSelect(this.current);
					}
					this.hide();
					break;
				case KeyCode.Escape:
					if (!this.clearTimeout) {
						this.clearTimeout = setTimeout(function () {
							self.clearTimeout = null;
						}, 300);
					} else {
						clearTimeout(this.clearTimeout);
						this.clearTimeout = null;

						this.savedValue = "";
						this.actualValue = "";
						this.input.val("");

						this.emit("select", { item: null });
						this.emit("empty");

						if (Util.isFunction(this.options.onSelect)) {
							this.options.onSelect(null);
						}
					}
					this.hide();
					break;
				case KeyCode.Down:
					if (!this.visible()) {
						this.load();
						this.show();
					} else {
						if (this.current < this.items.length - 1) {
							this.current++;
							this.highlight();
						}
					}
					break;
				case KeyCode.Up:
					if (!this.visible()) {
						this.load();
						this.show();
					} else {
						if (this.current > 0) {
							this.current--;
							this.highlight();
						}
					}
					break;
				case KeyCode.Enter:
					if (this.visible()) {
						this.onItemSelect(this.current);
					}
					break;
			}
		},

		onKeyUp: function () {

			if (!this.input.val() && this.actualValue) {
				this.savedValue = "";
				this.actualValue = "";
				this.input.val("");

				this.emit("empty");
				this.emit("select", { item: null });
			}
		},

		registerWindowEvent: function () {
			$(window).on("click", this.windowClickHandler);
		},

		unregisterWindowEvent: function () {
			$(window).off("click", this.windowClickHandler);
		},

		clear: function () {
			this.savedValue = "";
			this.actualValue = "";
			this.input.val("");
			this.emit("empty");
		},

		val: function (val) {
			if (val === undefined) {
				var item = this.input.data("item");
				return item ? JSON.parse(item) : null;
			} else {
				if (val === null) {
					this.input.val(null);
					this.input.data("item", null);
				} else {

					this.input.val(val ? val[this.column] : "");
					this.input.data("item", JSON.stringify(val));
				}
				this.input.trigger("change");
			}
		},

		onLiveChange: function () {
			var val = this.input.val();

			if (val != this.savedValue) {
				this.savedValue = val;

				this.load();

				if (!this.visible()) {
					this.show();
				}

				if (this.savedValue === "") {
					this.emit("empty");
				}
			}
		},

		onItemSelect: function (index) {
			var item = index === undefined ? null : this.items[index];

			if (item) {
				this.actualValue = item ? item[this.column] : "";
				this.input.val(this.actualValue);
				this.savedValue = this.actualValue;

				this.input.data("item", JSON.stringify(item));

				this.input.trigger("change");

				this.emit("select", { item: item });

				if (Util.isFunction(this.options.onSelect)) {
					this.options.onSelect(item);
				}
			}

			this.hide();
		}
	});
});
