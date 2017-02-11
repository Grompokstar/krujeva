Module.define(
	"Events",

	function () {

		KeyBoard = Static(Events, {
			keyboardHeight: null,
			$body: null,
			$element: null,

			initialScrollPosition: {},
			initialElementPosition: {},

			formInput: null,

			typeKeyBoard: null,

			keyCodes: {
				48: [0,0],
				49: [1,1],
				50: [2,2],
				51: [3,3],
				52: [4,4],
				53: [5,5],
				54: [6,6],
				55: [7,7],
				56: [8,8],
				57: [9,9],

				65: ['a', 'ф'],
				66: ['b','и'],
				67: ['c','с'],
				68: ['d','в'],
				69: ['e', 'у'],
				70: ['f', 'а'],
				71: ['g', 'п'],
				72: ['h','р'],
				73: ['i','ш'],
				74: ['j','о'],
				75: ['k', 'л'],
				76: ['l', 'д'],
				77: ['m', 'ь'],
				78: ['n','т'],
				79: ['o', 'щ'],
				80: ['p','з'],
				81: ['q', 'й'],
				82: ['r', 'к'],
				83: ['s', 'ы'],
				84: ['t','е'],
				85: ['u', 'г'],
				86: ['v','м'],
				87: ['w', 'ц'],
				88: ['x', 'ч'],
				89: ['y','н'],
				90: ['z','я'],
				219: ['[', 'х'],
				221: [']','ъ'],
				186: [';','ж'],
				222: ["'", 'э'],
				188: [',','б'],
				190: ['.','ю'],
				32: [' ', ' '],
				8: 'deleteCode'
			},

			initialize: function() {
				this.$body = $('body');

				$(window).on('keydown', function (ev) {

					if (!this.formInput) {
						return;
					}

					this.pushKeyCode(ev.keyCode);

					ev.stopPropagation();
					ev.preventDefault();

				}.bind(this));

				var self = this;

				this.windowResizeHandler = function () {
					self.onWindowResize();
				};
			},

			html: function () {
				return UI.Template.$render('Utils/Keyboard');
			},

			isVisible: function () {
				return this.$element;
			},

			show: function(formInput, options) {
				this.formInput = formInput;

				this.typeKeyBoard = 'text';

				this.lang = 1;

				this.emit('inputFocus', {formInput: formInput});

				this.initial(this.formInput.$element, formInput.$formListContainer);

				if (this.isVisible()) {

					this.calcButtonSize();

					if (this.formInput && this.formInput.$formListContainer) {
						this.center(this.formInput.$element, this.formInput.$formListContainer);
					}

				} else {

					this.$element = this.html();

					this.calcButtonSize();

					this.$element.appendTo(this.$body);

					this.keyboardHeight = this.$element.outerHeight();

					this.$body.transition({
						height: $('html').outerHeight() - this.keyboardHeight
					}, 80, 'linear', function () {

						if (this.formInput && this.formInput.$formListContainer) {
							this.center(this.formInput.$element, this.formInput.$formListContainer);
						}

					}.bind(this));

					$(window).off('resize', this.windowResizeHandler);

					$(window).on('resize', this.windowResizeHandler);
				}

				if (this.$element) {
					this.$element.off();
				}

				this.initEvent("touchstart", "on-touchstart", this.$element, this);
				this.initEvent("touchend", "on-touchend", this.$element, this);
			},


			timerLongTap: null,
			intervalLongTap: null,

			onKeyTouchStart: function (caller) {

				if (this.timerLongTap) {
					clearTimeout(this.timerLongTap);
					this.timerLongTap = null;
				}

				$(caller).addClass('active');

				var self = this;

				//delete key
				var keycode = $(caller).data('key');

				if (keycode == 8) {

					(function (keycode) {

						self.timerLongTap = setTimeout(function () {

							self.intervalLongTap = setInterval(function () {
								self.pushKeyCode(keycode)
							}, 100);

						}, 500);

					})(keycode);
				}
			},

			onKeyTouchEnd: function (caller) {

				if (this.timerLongTap) {
					clearTimeout(this.timerLongTap);
					this.timerLongTap = null;
				}

				if (this.intervalLongTap) {
					clearInterval(this.intervalLongTap);
					this.intervalLongTap = null;
				}

				this.pushKeyCode($(caller).data('key'));

				(function (caller) {

					setTimeout(function () {

						$(caller).removeClass('active');

					}, 100);

				})(caller);
			},

			pushKeyCode: function (keycode) {

				if (!this.formInput) {
					return;
				}

				if (typeof this.keyCodes[keycode] == 'undefined') {
					return;
				}

				var char = this.keyCodes[keycode];

				if (Util.isArray(char)) {
					char = char[this.lang];
				}

				switch (char) {
					case 'deleteCode':
						this.formInput.removeChar();
						break;

					default:
						this.formInput.val(char, true);
						break;
				}
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

			calcButtonSize: function () {
				var maxWidth = 1280;
				var margin = 6;
				var rowButtons = 11;

				var width = $('html').outerWidth();

				if (width > maxWidth) {
					width = maxWidth;
				}

				var coefficientWidth = 1;
				var coefficientHeight = 1;

				if (width < 500) {

					coefficientWidth = 2;
					coefficientHeight = 3;

				} else if (width < 960) {

					coefficientWidth = 3;
					coefficientHeight = 2;
				}

				var btnWidth = parseInt((width - (rowButtons* margin)) / rowButtons);
				var bthHeight = parseInt(btnWidth * coefficientHeight / coefficientWidth);

				var otherKeys = {
					'key-space': 7.7,
					'key-enter': 3,
					'key-hide': 2,
					'key-remove': 2.1
				};

				var styles = [];

				for (var i in otherKeys) if (otherKeys.hasOwnProperty(i)) {

					var w = parseInt(btnWidth * otherKeys[i]);

					styles.push('.'+i+' {width: '+w+'px}');
				}

				this.$element.find('.keyboard-style').html('<style>.key-button{width: '+ btnWidth+'px; height: '+ bthHeight+'px}'+ styles.join('')+'</style>');
			},

			initial: function ($element, $scroll) {

				if (!$element || !$scroll) {
					return;
				}

				var scrollHeight = $scroll[0]['scrollHeight'];

				var windowScrollHeight = $scroll.outerHeight();

				var scrollTop = $scroll.scrollTop() || 0;

				var offset = $scroll.offset();

				this.initialScrollPosition = {
					scrollHeight: scrollHeight,
					windowScrollHeight: windowScrollHeight,
					scrollTop: scrollTop,
					top: offset.top
				};

				this.initialElementPosition = $element.offset();
			},

			center: function ($element, $scroll) {

				if (!$element || !$scroll) {
					return;
				}

				var scrollHeight = $scroll[0]['scrollHeight'];

				var windowScrollHeight = $scroll.outerHeight();

				var scrollTop = $scroll.scrollTop() || 0;

				/*@todo var offset = $scroll.offset();*/

				var elHeight = $element.outerHeight();

				if (this.initialElementPosition.top + elHeight > windowScrollHeight) {

					var top = this.initialElementPosition.top - windowScrollHeight + elHeight;

					$scroll.scrollTop(scrollTop + top);
				}
			},

			resizeCenter: function ($element, $scroll) {

				if (!$element || !$scroll) {
					return;
				}

				var windowScrollHeight = $scroll.outerHeight();

				var elHeight = $element.outerHeight();

				var elementPosition = $element.offset();

				var scrollTop = $scroll.scrollTop() || 0;

				if (elementPosition.top + elHeight > windowScrollHeight) {

					var top = elementPosition.top - windowScrollHeight + elHeight;

					$scroll.scrollTop(scrollTop + top);
				}
			},

			onWindowResize: function () {

				this.calcButtonSize();

				this.keyboardHeight = this.$element.outerHeight();

				this.$body.css({
					height: $('html').outerHeight() - this.keyboardHeight
				});

				if (this.formInput && this.formInput.$formListContainer) {
					this.resizeCenter(this.formInput.$element, this.formInput.$formListContainer);
				}
			},

			hide: function () {

				if (this.$element) {

					this.$element.off();

					this.$element.remove();

					this.$element = null;
				}

				this.formInput = null;

				$(window).off('resize', this.windowResizeHandler);

				this.$body.removeAttr('style');
			}

		});
	}
);
