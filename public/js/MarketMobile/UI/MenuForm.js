Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.MenuForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/MenuForm",

			providerClick: function () {
				this.parent.open('provider', {animate: false});
				application.showMenu();
			},

			settingClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('setting', {animate: false});
			},

			exitClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('welcome', {animate: false});
			},

			cartClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('cart', {animate: false, closeOtherPages: true});
			},

			bonusClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('bonus', {animate: false});
			},

			historyClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('history', {animate: false});
			},

			updateAppClick: function () {
				application.showMenu({animate: false, mode: 'hide'});
				this.parent.open('updateapp', {animate: false});
			},

            getMenuOffset: function () {

            	var mainWidht = application.getMainWidth();

            	if (mainWidht < 260) {
            		return 30;
            	}

                return 60;
            },

            isVisibleMenu: function () {
               return this.$element.hasClass('opened-menu');
            },

			moveEvent: {},
			listenMovePanel: true,

            initialize: function () {
            	this.ParentCall();

            	var self = this;

				application.cartForm.on('changed', this.onCartChanged, this);

				this.onResizeHandler = function () {
					self.setWidth();
				};

				$(window).on('resize', this.onResizeHandler);

				this.onTouchStartHandler = function (caller) {
					self.onCntTouchStart(caller);
				};

				this.onTouchMoveHandler = function (caller) {
					caller.preventDefault();
					self.onCntTouchMove(caller);
				};

				this.onTouchEndHandler = function (caller) {
					self.onCntTouchEnd(caller);
				};

				$('#move-main-panelcnt').on('touchstart', this.onTouchStartHandler);
				$('#move-main-panelcnt').on('touchmove', this.onTouchMoveHandler);
				$('#move-main-panelcnt').on('touchend', this.onTouchEndHandler);
            },

			destroy: function () {

				application.cartForm.off('changed', this.onCartChanged, this);

				$(window).off('resize', this.onResizeHandler);

				$('#move-main-panelcnt').off('touchstart', this.onTouchStartHandler);

				$('#move-main-panelcnt').off('touchmove', this.onTouchMoveHandler);

				$('#move-main-panelcnt').off('touchend', this.onTouchEndHandler);

				this.ParentCall();
			},

            render: function () {
            	this.ParentCall();

            	this.setWidth();

            	return this;
            },

            setWidth: function () {
            	this.$element.width((application.getMainWidth() - this.getMenuOffset()));
            },

			showMenu: function ($maincontainer, mainWidth, options) {

                options = Util.object(options);

                var needAnimate = typeof options.animate == 'undefined' ? true : options.animate;

                var toogle = typeof options.toogle == 'undefined' ? true : options.toogle;

				var firstStyle = [];

				var secondStyle = [];

				var width = mainWidth - this.getMenuOffset();

                var mode = 'show';

                if (toogle && this.isVisibleMenu()) {
                    mode = 'hide';
                }

				if (options.mode) {
					mode = options.mode;
				}


				if (mode == 'hide') {

					this.$element.removeClass('opened-menu');

					firstStyle.push('-moz-transform: translate3d(' + width + 'px, 0, 0);');
					firstStyle.push('-o-transform: translate3d(' + width + 'px, 0, 0);');
					firstStyle.push('-webkit-transform: translate3d(' + width + 'px, 0, 0);');
					firstStyle.push('transform: translate3d(' + width + 'px, 0, 0);');

					secondStyle.push('-moz-transform: translate3d(0, 0, 0);');
					secondStyle.push('-o-transform: translate3d(0, 0, 0);');
					secondStyle.push('-webkit-transform: translate3d(0, 0, 0);');
					secondStyle.push('transform: translate3d(0, 0, 0);');

				} else {

					this.$element.addClass('opened-menu');

					firstStyle.push('-moz-transform: translate3d(0, 0, 0);');
					firstStyle.push('-o-transform: translate3d(0, 0, 0);');
					firstStyle.push('-webkit-transform: translate3d(0, 0, 0);');
					firstStyle.push('transform: translate3d(0, 0, 0);');

					secondStyle.push('-moz-transform: translate3d(' + width + 'px, 0, 0);');
					secondStyle.push('-o-transform: translate3d(' + width + 'px, 0, 0);');
					secondStyle.push('-webkit-transform: translate3d(' + width + 'px, 0, 0);');
					secondStyle.push('transform: translate3d(' + width + 'px, 0, 0);');
				}

				if (!needAnimate) {
					secondStyle.push('transition: none;');
				}

                if (needAnimate) {

                    $maincontainer.attr('style', firstStyle.join(''));

                    setTimeout(function () {

                        $maincontainer.attr('style', secondStyle.join(''));

                        $maincontainer.one($.support.transition.end, function () {

                            if (mode == 'hide') {
                                $maincontainer.removeAttr('style');
                            }

                        });

                    }, 100);

                } else {

                    $maincontainer.attr('style', secondStyle.join(''));

					if (mode == 'hide') {
						$maincontainer.removeAttr('style');
					}

                }
			},







			onCntTouchStart: function (ev) {

				var event = ev.originalEvent.touches[0];

				this.moveEvent = {
					x: event.clientX,
					y: event.clientY
				};
			},

			onCntTouchEnd: function (ev) {

				if (!this.listenMovePanel) {
					return;
				}

				if (!this.moveEvent['x']) {
					return;
				}

				var movedX = this.moveEvent['elementCurrentX'] || 0;

				var maxX = application.getMainWidth() - this.getMenuOffset();

				var persentMove = movedX * 100 / maxX;

				var style = [];

				var isCloseMenu = false;

				var styleXposition = 0;

				if (persentMove > 40) {

					styleXposition = maxX;

					style.push('-moz-transform: translate3d(' + maxX + 'px, 0, 0);');
					style.push('-webkit-transform: translate3d(' + maxX + 'px, 0, 0);');
					style.push('transform: translate3d(' + maxX + 'px, 0, 0);');
					style.push('transition: -webkit-transform 0.1s ease-in;');
					style.push('transition: -moz-transform 0.1s ease-in;');
					style.push('transition: transform 0.1s ease-in;');

				} else {

					isCloseMenu = true;

					style.push('-moz-transform: translate3d(0, 0, 0);');
					style.push('-webkit-transform: translate3d(0, 0, 0);');
					style.push('transform: translate3d(0, 0, 0);');
					style.push('transition: -webkit-transform 0.1s ease-in;');
					style.push('transition: -moz-transform 0.1s ease-in;');
					style.push('transition: transform 0.1s ease-in;');
				}

				var $elMoveContainer = application.$page();

				$elMoveContainer.removeClass('move-animated');

				var endAnimateContainer = function (isCloseMenu) {

					if (isCloseMenu) {

						application.showMenu({animate: false, mode: 'hide'});

					} else {

						application.showMenu({animate: false, toogle: false});

					}
				};

				this.listenMovePanel = false;


				if (styleXposition == movedX) {

					this.moveEvent = {};

					endAnimateContainer(isCloseMenu);

					this.listenMovePanel = true;

				} else {

					this.moveEvent = {};

					$elMoveContainer.attr('style', style.join(''));

					$elMoveContainer.one($.support.transition.end, function () {

						endAnimateContainer(isCloseMenu);

						this.listenMovePanel = true;

					}.bind(this));

				}
			},

			onCntTouchMove: function (ev) {

				if (!this.moveEvent['x']) {
					return;
				}

				var event = ev.originalEvent.touches[0];

				if (!this.listenMovePanel) {
					return;
				}

				//если не открыто меню - не слушаем
				if (!this.isVisibleMenu()) {
					this.moveEvent['x'] = null;
					return;
				}

				var diffX = parseInt(event.clientX - this.moveEvent['x']);

				var maxX = application.getMainWidth() - this.getMenuOffset();

				//посчитаем на какой позиции теперь должен быть элемент
				if (!this.moveEvent['elementOffsetX']) {
					this.moveEvent['elementOffsetX'] = maxX;
				}

				var needPosition = this.moveEvent['elementOffsetX'] + diffX;

				if (needPosition < 0) {
					needPosition = 0;
				}

				if (needPosition > maxX) {
					needPosition = maxX;
				}

				var $elMoveContainer = application.$page();

				var style = [];

				this.moveEvent['elementCurrentX'] = needPosition;

				style.push('-moz-transform: translate3d(' + needPosition + 'px, 0, 0);');
				style.push('-webkit-transform: translate3d(' + needPosition + 'px, 0, 0);');
				style.push('transform: translate3d(' + needPosition + 'px, 0, 0);');
				style.push('transition: none;');

				$elMoveContainer.addClass('move-animated').attr('style', style.join(''));
			},

			onCartChanged: function () {
				var cartCount = application.cartForm.countInCart();

				if (cartCount) {

					this.$name('menu-cart-count').html('<div class="notification">'+ cartCount +'</div>');

				} else {

					this.$name('menu-cart-count').empty();

				}
			}
		});
	}
);