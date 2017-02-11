Module.define("Events",

	function () {

		NumKeyboard = Class(Events, {
			template: "Utils/NumKeyboard",
			$place: null,
			$element: null,
			parent: null,

			keyCodes: {
				48: [0, 0],
				49: [1, 1],
				50: [2, 2],
				51: [3, 3],
				52: [4, 4],
				53: [5, 5],
				54: [6, 6],
				55: [7, 7],
				56: [8, 8],
				57: [9, 9]
			},

			initialize: function (parent, options) {
				this.parent = parent;

				this.parent.on('destroy', this.onParentDestroy, this);

				this.$place = options.place;

				this.$element = this.html();

				this.$element.appendTo(this.$place);

				this.calcButtonSize();

				setTimeout(this.calcButtonSize.bind(this), 300);

				var self = this;

				this.windowResizeHandler = function () {
					self.onWindowResize();
				};

				this.keyUpHandler = function (ev, e) {
					self.onkeyUp(ev, e);
				};

				$(window).on('resize', this.windowResizeHandler);
				$(window).on('keyup', this.keyUpHandler);

				this.initEvent("touchstart", "on-touchstart", this.$element, this);
				this.initEvent("touchend", "on-touchend", this.$element, this);

				if (!this.isTouch()) {
					this.initEvent("click", "on-click", this.$element, this);
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

			destroy: function () {

				if (this.$element) {
					this.$element.off();
				}

				$(window).off('resize', this.windowResizeHandler);
				$(window).off('keyup', this.keyUpHandler);

				this.parent.off('destroy', this.onParentDestroy, this);

				this.$place = null;

				this.$element = null;

				this.parent = null;
			},

			html: function () {
				return UI.Template.$render(this.template);
			},

			onParentDestroy: function () {
				this.destroy();
			},

			calcByRem: function (size, rootSize) {
				return parseInt(rootSize * size / 16);
			},

			onWindowResize: function () {
				this.calcButtonSize();
			},

			onkeyUp: function (e) {

				if (!this.keyCodes[e.keyCode]) {
					return;
				}

				this.pushKeyCode(this.keyCodes[e.keyCode][0]);
			},

			calcButtonSize: function () {
				var rootSize = parseFloat($('html').css('font-size').replace('px', ''));

				var marginTopBotton = this.calcByRem(30, rootSize);

				if (!this.$place) {
					return;
				}

				var height = this.$place.outerHeight() - (marginTopBotton * 2);

				var marginBottomBtn = this.calcByRem(10, rootSize);

				var rows = 4;

				var maxHeight = this.calcByRem(90, rootSize);

				var keyHeight = parseInt((height - (marginBottomBtn * (rows - 1))) / rows);

				if (keyHeight > maxHeight) {
					keyHeight = maxHeight;
				}

				if (keyHeight < 0) {
					keyHeight = 0;
				}

				this.$element.find('.keyboard-style').html('<style>.num-symbol{width: ' + keyHeight + 'px; height: ' + keyHeight + 'px; line-height: ' + keyHeight + 'px}</style>');
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
				$(caller).addClass('active');
			},

			pushKeyCode: function (key) {
				this.emit("Key.Click", {key: key});
			},

			onKeyTouchEnd: function (caller) {

				this.pushKeyCode($(caller).data('key'));

				CityTablet.Cordova.vibrate(20);

				(function (caller) {

					setTimeout(function () {

						$(caller).removeClass('active');

					}, 0);

				})(caller);
			}
		});

	});