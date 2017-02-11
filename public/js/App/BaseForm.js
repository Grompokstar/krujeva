Module.define(

	function () {
		NS("App");

		App.BaseForm = Class({
			$container: null,

			$: function (selector, nullable) {
				var element = this.$container ? $(selector, this.$container) : $();

				if (nullable && element && !element.length) {
					element = null;
				}

				return element;
			},

			$name: function (name, nullable) {
				return this.$("[name='" + name + "']", nullable);
			},

			initInteractionEvents: function () {

				this.destroyEvents(this.$container);

				this.Class.initEvent("click", "on-click", this.$container, this);
				this.Class.initEvent("mouseover", "on-mouseover", this.$container, this);
				this.Class.initEvent("mouseout", "on-mouseout", this.$container, this);
				this.Class.initEvent("change", "on-change", this.$container, this);
				this.Class.initEvent("keyup", "on-keyup", this.$container, this);
				this.Class.initEvent("keydown", "on-keydown", this.$container, this);
				this.Class.initEvent("input", "on-input", this.$container, this);
				this.Class.initEvent("touchmove", "on-touchmove", this.$container, this);
				this.Class.initEvent("contextmenu", "on-contextmenu", this.$container, this);
				this.Class.initEvent("touchstart", "on-touchstart", this.$container, this);
				this.Class.initEvent("touchend", "on-touchend", this.$container, this);
			},

			destroyEvents: function ($elementHTML) {

				$elementHTML = $elementHTML || this.$element;

				if (!$elementHTML) {
					return;
				}

				$elementHTML.off();
			},

			formRecord: function ($form) {

				var record = {};

				$form.find('[name]').each(function () {

					var $el = $(this);

					if (typeof $el.val != 'undefined') {
						record[$el.attr('name')] = $el.val();
					}
				});

				return record;
			},

			initEditor: function () {

				if (this.formed) {
					return;
				}

				this.formed = true;

				jQuery.suitUp.commands.forecolor = function (callback) {
					var blackBackground = $('<div/>').css({
							background: '#fff',
							position: 'absolute',
							top: 0,
							left: 0,
							opacity: .5,
							width: '100%',
							height: '100%'
						}).on('click',function () {
								popup.add(this).remove();
							}).appendTo('.suitup'),

						popup = $('<div/>').appendTo('.suitup').addClass("color_class_suit");

					$('<h4/>').appendTo(popup).text('Выберете цвет');

					var colors = [
						'black', '#d00000', '#fd0200', '#f5ca02', '#fffc00', '#89d53d', '#00b449', '#00b3e2', '#006bc5', '#00216b', '#6d329c', '#ecece2', '#224879', '#4485c9', '#bf5548', '#9bbe56', '#7b6691', '#58a9bc', '#fa963e', '#f3f3f3', '#d8d8d8', '#bfbfbf', '#a5a5a5', '#7f7f7f', '#58595b', '#e3dacb', '#c5bd98', '#c5bd98', '#c5bd98', '#528dd9', '#debcba', '#d19191', '#933b37', '#62271f', '#d4e3b8', '#7a9439', '#51671f', '#cec2d6', '#b0a3ce', '#5f487c', '#402e56', '#b4deea', '#93ced6', '#2f86a1', '#1f5965', '#1f5965', '#e36e0f', '#8f4a11'
					];

					for (var i in colors) {
						$('<div/>').css({
							background: colors[i]
						}).appendTo(popup).addClass("color_class_suit_element").on('click', function () {
								blackBackground.add(popup).remove();
								callback($(this).css("background"));
							});
					}
				}

				$('.suitup-textarea').each(function () {
					$(this).suitUp('bold', 'italic', 'underline', 'strikethrough', '|', 'formatblock#<h1>', 'formatblock#<h2>', 'formatblock#<h3>', 'formatblock#<h4>', 'formatblock#<h5>', 'formatblock#<h6>', '|', 'insertimage', '|', 'link', '|', 'forecolor');
				});
			},
		});

		App.BaseForm.Static({

			$element: function (element) {
				if (Class.Is(element, UI.Element)) {
					return element.$element;
				}

				return $(element);
			},

			initEvent: function (event, name, element, object) {
				var selector = "[" + name + "]";

				var callback = function (args) {
					var handler = $(this).attr(name);

					if (Util.isFunction(object[handler])) {
						if (object[handler].call(object, this, args) === false) {
							args.stopPropagation();
						}
					}
				};

				if (element) {
					element.filter(selector).on(event, callback);
					element.on(event, selector, callback);
				}
			},
		});
	});

