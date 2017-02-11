Module.define(
	"UI.Widget.Base",

	function () {
		NS("UI.Widget");

		UI.Widget.Bar = Class(UI.Widget.Base, {
			template: "UI/Widget/Bar/Bar",

			styles: ["bar__header"],

			appendLeft: function (element) {
				element = UI.Element.$element(element);

				element.appendTo(this.getLeftContainer());
			},

			prependLeft: function (element) {
				element = UI.Element.$element(element);

				element.prependTo(this.getLeftContainer());
			},

			appendRight: function (element) {
				element = UI.Element.$element(element);

				element.prependTo(this.getRightContainer());
			},

			prependRight: function (element) {
				element = UI.Element.$element(element);

				element.appendTo(this.getRightContainer());
			},

			getLeftContainer: function () {
				this.createLeft();

				return this.$(" > .bar__left");
			},

			getRightContainer: function () {
				this.createRight();

				return this.$("> .bar__right");
			},

			createLeft: function () {
				if (this.$element && !this.$(" > .bar__left").length) {
					this.$element.prepend('<div class="bar__left"></div>');
				}
			},

			createRight: function () {
				if (this.$element && !this.$(" > .bar__right").length) {
					this.$element.append('<div class="bar__right"></div>');
				}
			}
		});
	}
);
