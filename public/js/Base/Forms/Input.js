Module.define(

	function () {
		NS("Base.Forms");

		Base.Forms.Input = Class(Events, {
			$element: null,
			template: "Base/Forms/Input",

			$formListContainer: null,

			initialize: function () {
				KeyBoard.on('inputFocus', this.onInputFocus, this);
			},

			destroy: function () {
				KeyBoard.off('inputFocus', this.onInputFocus, this);

				if (this.$element) {

					this.$element.off();

					this.$element.remove();
				}

				this.$element = null;

				this.$formListContainer = null;
			},

			render: function (htmlOptions) {

				if (htmlOptions.class) {
					htmlOptions.class += ' custom-input';
				} else {
					htmlOptions.class = 'custom-input';
				}

				var value = htmlOptions.value ? htmlOptions.value : '';

				return UI.Template.render(this.template, {htmlOptions: Base.FieldForm.getHtmlOptions(htmlOptions), value: value});
			},

			initEvents: function ($element, $list) {

				this.$element = $element;

				if (this.$element) {
					this.$element.off();
				}

				this.$formListContainer = $list;

				this.initEvent(this.$element, 'click', this.onClickInput, this);

				var self = this;

				this.onMainClickHandler = function () {
					self.onMainClick();
				};
			},

			onClickInput: function (caller, ev) {

				ev.preventDefault();

				ev.stopPropagation();

				//this.$element.addClass("focused");

				KeyBoard.show(this);

				this.offMapClick();

				$('#main-container').on('click', this.onMainClickHandler);
			},

			offMapClick: function () {
				$('#main-container').off('click', this.onMainClickHandler);
			},

			onMainClick: function () {
				this.offMapClick();

				this.$element.removeClass("focused");

				if (KeyBoard.isVisible()) {
					KeyBoard.hide();
				}
			},

			initEvent: function (element, event, cb, object) {

				var callback = function (args) {

					if (Util.isFunction(cb)) {
						if (cb.call(object, this, args) === false) {
							args.stopPropagation();
						}
					}
				};

				if (element) {
					element.on(event, callback);
				}
			},

			val: function (value, append) {

				if (typeof value == 'undefined') {
					return $('.text-container', this.$element).html().trim().replace(/&nbsp;/g, " ");
				}

				append = typeof append !== 'undefined';

				if (append) {
					value = this.val() + value;
				}

				value = value.replace(/ /g, "&nbsp;");

				this.$element.find('.text-container').html(value);
			},

			removeChar: function () {
				var value = this.val();
				this.val(value.slice(0, -1));
			},

			onInputFocus: function (args) {

				if (this === args.formInput) {

					this.$element.addClass("focused");

				} else {

					this.$element.removeClass("focused");
				}
			}
		});
	});