Module.define("Base.InfinityScroll",

	function () {

		NS("KrujevaMobile");

		KrujevaMobile.PageSwitcher = Class({

			moveEvent: {},

			listenMovePanel: true,

			bodyTouchStart: false,

			initialize: function () {
				this.ParentCall();

				var self = this;

				this.onTouchStartHandler = function (caller) {
					self.onBodyTouchStart(caller);
				};

				this.onTouchMoveHandler = function (caller) {
					caller.preventDefault();
					self.onBodyTouchMove(caller);
				};

				this.onTouchEndHandler = function (caller) {
					self.onBodyTouchEnd(caller);
				};

				$('body').on('touchstart', this.onTouchStartHandler);

				$('body').on('touchmove', this.onTouchMoveHandler);

				$('body').on('touchend', this.onTouchEndHandler);
			},

			destroy: function () {

				$('body').off('touchstart', this.onTouchStartHandler);

				$('body').off('touchmove', this.onTouchMoveHandler);

				$('body').off('touchend', this.onTouchEndHandler);

				this.ParentCall();
			},


			listenPanel: function () {
				this.listenMovePanel = true;
			},

			unlistenPanel: function () {
				this.listenMovePanel = false;
			},

			onBodyTouchStart: function (ev) {

				var event = ev.originalEvent.touches[0];

				this.moveEvent = {
					x: event.clientX,
					y: event.clientY
				};

				this.bodyTouchStart = true;
			},

			onBodyTouchEnd: function (ev) {

				//@hide keyboard
				if (this.bodyTouchStart) {
					this.bodyTouchStart = false;

					//$('input, textarea').blur();
				}

				if (!this.listenMovePanel) {
					return;
				}

				if (!this.moveEvent['x']) {
					return;
				}

				var moveDirection = 'down';

				if (this.moveEvent && this.moveEvent['scroll'] && this.moveEvent['scroll'] == 'left') {
					moveDirection = 'left';
				}

				if (moveDirection != 'left') {
					this.moveEvent = {};
					return;
				}

				var movedX = this.moveEvent['elementOffsetX'] || 0;

				var $elMoveContainer = application.openedPages[application.currentOpenedPage].$element;

				var isMenuPage = false;

				if (this.moveEvent['page'] == 'widget.Menu') {

					$elMoveContainer = application.$page();

					isMenuPage = true;
				}

				if (!movedX) {

					this.moveEvent = {};

					$elMoveContainer.removeClass('move-animated');

					if (!isMenuPage) {

						$elMoveContainer.attr('style', 'z-index: ' + $elMoveContainer.css('zIndex') + ';');

						application.hideOtherPages(application.currentOpenedPage);

					} else {

						$elMoveContainer.removeAttr('style');
					}

					return;
				}

				//надо понять что делать - либо обратно закрыть либо открыть
				var mainWidth = application.getMainWidth();

				var style = ['z-index:5;'];

				if (this.moveEvent['page'] == 'widget.Menu') {

					style = [];

					mainWidth = mainWidth - application.menuForm.getMenuOffset();
				}

				var persentMove = movedX * 100 / mainWidth;

				var isBackPageAnimate = false;

				var styleXposition = 0;

				if (persentMove > 40) {

					isBackPageAnimate = true;

					styleXposition = mainWidth;

					style.push('-moz-transform: translate3d(' + mainWidth + 'px, 0, 0);');
					style.push('-webkit-transform: translate3d(' + mainWidth + 'px, 0, 0);');
					style.push('transform: translate3d(' + mainWidth + 'px, 0, 0);');
					style.push('transition: -webkit-transform 0.1s ease-in;');
					style.push('transition: -moz-transform 0.1s ease-in;');
					style.push('transition: transform 0.1s ease-in;');

				} else {

					style.push('-moz-transform: translate3d(0, 0, 0);');
					style.push('-webkit-transform: translate3d(0, 0, 0);');
					style.push('transform: translate3d(0, 0, 0);');
					style.push('transition: -webkit-transform 0.1s ease-in;');
					style.push('transition: -moz-transform 0.1s ease-in;');
					style.push('transition: transform 0.1s ease-in;');
				}

				var endAnimateContainer = function (context, $el, isMenuPage, isBackPageAnimate) {

					//пред страницу надо открыть до конца
					if (!isMenuPage && isBackPageAnimate) {

						context.backOpenPage(false, {animate: false});

						return;

					} else if (!isMenuPage) {

						$el.attr('style', 'z-index: ' + $el.css('zIndex') + ';');

						context.hideOtherPages(context.currentOpenedPage);

						return;
					}

					//меню надо открыть до конца
					if (isMenuPage && isBackPageAnimate) {

						context.showMenu({animate: false, toogle: false});

						return;

					} else if (isMenuPage) {

						$el.removeAttr('style');

						return;
					}
				};

				$elMoveContainer.removeClass('move-animated');

				this.listenMovePanel = false;

				if (styleXposition == this.moveEvent['elementOffsetX']) {

					this.moveEvent = {};

					endAnimateContainer(application, $elMoveContainer, isMenuPage, isBackPageAnimate);

					this.listenMovePanel = true;

				} else {

					this.moveEvent = {};

					$elMoveContainer.attr('style', style.join(''));

					$elMoveContainer.one($.support.transition.end, function () {

						endAnimateContainer(application, $elMoveContainer, isMenuPage, isBackPageAnimate);

						this.listenMovePanel = true;

					}.bind(this));
				}

			},

			onBodyTouchMove: function (ev) {

				//если вниз крутим - то ок - пусть вниз крутим
				if (this.moveEvent && this.moveEvent['scroll'] && this.moveEvent['scroll'] == 'down') {
					return;
				}

				//если влево то выключаем скрол
				if (this.moveEvent && this.moveEvent['scroll'] && this.moveEvent['scroll'] == 'left') {
					ev.stopPropagation();
					ev.preventDefault();
				}

				if (!this.moveEvent['x']) {
					return;
				}

				var event = ev.originalEvent.touches[0];

				//если скролл идет - тогда не слушаем клики
				var clckdiffX = Math.abs(event.clientX - this.moveEvent['x']);
				var clckdiffY = Math.abs(event.clientY - this.moveEvent['y']);
				if (clckdiffX > 20 || clckdiffY > 10) {
					application.emit('global.click.clear');
				}
				//клаву скрыть или нет
				if (clckdiffX > 10 || clckdiffY > 10) {
					this.bodyTouchStart = false;
				}
				//@end listen click

				if (!this.listenMovePanel) {
					return;
				}

				//слушаем только скрая экрана - первые 100 пикселей
				if (this.moveEvent['x'] > 100) {
					return;
				}

				//если открыто меню - по другому нужно двигать страницу
				if (application.menuForm && application.menuForm.isVisibleMenu()) {
					this.moveEvent['x'] = null;
					return;
				}

				//пытаеемся определить в какое направление перемещаем палец
				if (!this.moveEvent['scroll']) {

					var diffX = (event.clientX - this.moveEvent['x']);

					var diffY = Math.abs(event.clientY - this.moveEvent['y']);

					//в итоге скролл вбок
					if (diffX > 1) {
						this.moveEvent['scroll'] = 'left';

						//в итоге скролл вниз пошел
					} else if (diffY > 10) {
						this.moveEvent['scroll'] = 'down';
					}

					if (!this.moveEvent['scroll']) {
						return;
					}
				}

				//теперь надо понять что двигать - либо эту страницу - либо меню нужно показать ))
				if (!this.moveEvent['page']) {

					var lastPage = application.backOpenPage(true);

					//надо открывать меню
					if (!lastPage) {

						if (!application.menuForm) {

							this.moveEvent['x'] = null;

							return;

						} else {

							this.moveEvent['page'] = 'widget.Menu';
						}

						//надо открывать предыдущую страницу - а текущую двигать
					} else {

						this.moveEvent['page'] = lastPage;

						//вниз ставим страницу
						application.open(lastPage, {backClick: true, backMoveMode: true});
					}

					//что бы не дергалась начальная анимация
					this.moveEvent['x'] = event.clientX;
				}

				var diffX = parseInt(event.clientX - this.moveEvent['x']);

				var maxX = application.getMainWidth();

				if (diffX < 0) {
					diffX = 0;
				}

				if (application.menuForm && this.moveEvent['page'] == 'widget.Menu') {
					maxX -= application.menuForm.getMenuOffset();
				}

				if (diffX > maxX) {
					diffX = maxX;
				}

				//посчитаем на какой позиции теперь должен быть элемент
				this.moveEvent['elementOffsetX'] = (diffX);

				var $elMoveContainer = application.openedPages[application.currentOpenedPage].$element;

				var style = ['z-index:5;'];

				if (this.moveEvent['page'] == 'widget.Menu') {

					$elMoveContainer = application.$page();

					style = [];
				}

				style.push('-moz-transform: translate3d(' + this.moveEvent['elementOffsetX'] + 'px, 0, 0);');
				style.push('-webkit-transform: translate3d(' + this.moveEvent['elementOffsetX'] + 'px, 0, 0);');
				style.push('transform: translate3d(' + this.moveEvent['elementOffsetX'] + 'px, 0, 0);');
				style.push('transition: none;');

				$elMoveContainer.addClass('move-animated').attr('style', style.join(''));
			},

		});

	});