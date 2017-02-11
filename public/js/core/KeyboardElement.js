Module.define("Events",

	function () {

		KeyboardElement = Class(Events, {
			template: "Utils/KeyboardElement",
			$place: null,
			$element: null,
			parent: null,
			keyboardHeight: null,
			type: 'text',

			keyCodesNumber: {
				48: [0, 0],
				49: [1, 1],
				50: [2, 2],
				51: [3, 3],
				52: [4, 4],
				53: [5, 5],
				54: [6, 6],
				55: [7, 7],
				56: [8, 8],
				57: [9, 9],
				8: 'deleteCode',
				//13: 'enterCode'
			},

			keyCodesText: {
				65: ['a', 'ф'],
				66: ['b', 'и'],
				67: ['c', 'с'],
				68: ['d', 'в'],
				69: ['e', 'у'],
				70: ['f', 'а'],
				71: ['g', 'п'],
				72: ['h', 'р'],
				73: ['i', 'ш'],
				74: ['j', 'о'],
				75: ['k', 'л'],
				76: ['l', 'д'],
				77: ['m', 'ь'],
				78: ['n', 'т'],
				79: ['o', 'щ'],
				80: ['p', 'з'],
				81: ['q', 'й'],
				82: ['r', 'к'],
				83: ['s', 'ы'],
				84: ['t', 'е'],
				85: ['u', 'г'],
				86: ['v', 'м'],
				87: ['w', 'ц'],
				88: ['x', 'ч'],
				89: ['y', 'н'],
				90: ['z', 'я'],
				219: ['[', 'х'],
				221: [']', 'ъ'],
				186: [';', 'ж'],
				222: ["'", 'э'],
				188: [',', 'б'],
				190: ['.', 'ю'],
				32: [' ', ' '],
				8: 'deleteCode',
				//13: 'enterCode'
			},

			initialize: function (parent, options) {
				this.parent = parent;

				this.parent.on('destroy', this.onParentDestroy, this);

				this.$place = options.place;

				if (options && options.type) {

					this.type = options.type;
				}

				this.$element = this.html();

				this.initCSS(options);

				this.$element.appendTo(this.$place);

				//this.calcButtonSize();

				//setTimeout(this.calcButtonSize.bind(this), 300);

				var self = this;

				this.windowResizeHandler = function () {
					self.onWindowResize();
				};

				this.keyUpHandler = function (ev, e) {
					self.onkeyUp(ev, e);
				};

				$(window).on('resize', this.windowResizeHandler);

				setTimeout(function () {
					this.initEvent("touchstart", "on-touchstart", this.$element, this);

					this.initEvent("touchend", "on-touchend", this.$element, this);

					if (!this.isTouch()) {
						this.initEvent("click", "on-click", this.$element, this);
					}

				}.bind(this), 0);
			},

			initCSS: function (options) {

				if (options && options.animate) {

					this.$element.css({
						opacity: 0,
						y: 0
					});

					this.$element.addClass('absolute-keyboard-element');
				}
			},

			isTouch: function () {
				try {
					document.createEvent("TouchEvent");
					return true;
				}
				catch (e) {
					return false;
				}
			},

			changeType: function (type) {

				if (type == 'text') {

					this.type = 'text';

					this.$element.find('[name=numeric]').transition({
						opacity: 0
					}, 300, function () {
						$(this).hide();
					});

					this.$element.find('[name=text]').transition({
						opacity: 1
					}, 300);

				} else {

					this.$element.find('[name=numeric]').show().transition({
						opacity: 1
					}, 300);

					this.$element.find('[name=text]').transition({
						opacity: 0.1
					}, 300);

					this.type = 'numeric';
				}

			},

			animated: false,
			queue: [],

			show: function (callback ) {

				if (this.$element.css('opacity') == 1) {
					return;
				}

				if (this.animated) {
					this.queue.push('show');
					return;
				}

				this.initCSS({animate: true});

				this.emit('Show');

				$(window).on('keyup', this.keyUpHandler);

				this.keyboardHeight = this.$element.find('[name=keyboard-cnt]').outerHeight();

				this.animated = true;

				this.$element.transition({
					opacity: 1,
					y: this.keyboardHeight * -1
				}, 300, function () {

					this.animated = false;

					this.$element.removeClass('absolute-keyboard-element').css({y:0});

					if (Util.isFunction(callback)) {
						callback();
					}

					this.emit('Show');

					this.queueStart();

				}.bind(this));
			},

			queueStart: function () {

				if (!this.queue.length) {
					return;
				}

				var method = this.queue[0];

				this.queue.splice(0, 1);

				this[method]();
			},

			hide: function () {

				if (this.animated) {
					this.queue.push('hide');
					return;
				}

				if (this.$element.css('opacity') == 0) {
					return;
				}

				this.keyboardHeight = this.$element.find('[name=keyboard-cnt]').outerHeight();

				this.animated = true;

				this.$element.addClass('absolute-keyboard-element');

				this.emit('Hide');

				$(window).off('keyup', this.keyUpHandler);

				this.$element.css({y: this.keyboardHeight * -1}).transition({
					opacity: 0,
					y: this.keyboardHeight
				}, 300, function () {

					this.animated = false;

					this.$element.addClass('absolute-keyboard-element').css({y: 0});

					this.keyboardHeight = this.$element.find('[name=keyboard-cnt]').outerHeight();

					this.emit('Hide');

					this.queueStart();

				}.bind(this));
			},

			destroy: function () {

				if (this.$element) {
					this.$element.off();
				}

				$(window).off('resize', this.windowResizeHandler);

				this.parent.off('destroy', this.onParentDestroy, this);

				this.$place = null;

				this.$element = null;

				this.parent = null;
			},

			html: function () {
				return UI.Template.$render(this.template, {widget: this});
			},

			onParentDestroy: function () {
				this.destroy();
			},

			onWindowResize: function () {
				//this.calcButtonSize();
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

			onKeyTouchStart: function (caller) {

				if (this.type !== $(caller).data('type')) {
					return;
				}

				$(caller).addClass('active');
			},

			onkeyUp: function (e, ev) {

				var codes = this.keyCodesNumber;

				if (this.type == 'text') {
					codes = this.keyCodesText;
				}

				if (!codes[e.keyCode]) {
					return;
				}

				if (!Util.isArray(codes[e.keyCode])) {
					this.pushKeyCode(codes[e.keyCode]);
					return;
				}

				this.pushKeyCode(codes[e.keyCode][1]);
			},

			pushKeyCode: function (key) {
				this.emit("Key.Click", {key: key});
			},

			onKeyTouchEnd: function (caller) {

				if (this.type !== $(caller).data('type')) {
					return;
				}

				this.pushKeyCode(KeyBoard.keyCodes[$(caller).data('key')][1]);

				CityTablet.Cordova.vibrate(20);

				(function (caller) {

					setTimeout(function () {

						$(caller).removeClass('active');

					}, 0);

				})(caller);
			},
		})
	}
);