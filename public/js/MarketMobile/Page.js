Module.define(
	"UI.Element",
	"UI.Template",
	"Base.Hash",

	function () {
		NS("KrujevaMobile");

		KrujevaMobile.Page = Class(UI.Element, {
			parent: null,

			template: null,

			pages: {},

			widgets: {},

			openedPages: {},

			hashLevel: null,

			defaultPage: null,

			currentOpenedPage: null,

			appended: false,

			initialize: function (parent, options) {

				this.override(["template", "hashLevel"], options);

				this.parent = parent;

				this.Parent(options);

				if (this.parent && Class.Is(this.parent, Events)) {
					this.parent.on("destroy", this.onParentDestroy, this);
				}

				//#hashLevel
				if (parent && this.Is(KrujevaMobile.Page)) {

					if (typeof parent.hashLevel !== 'undefined') {
						this.hashLevel = parent.hashLevel + 1;
					}
				}

				if (typeof application != 'undefined') {
					application.on('global.click.clear', this.onGlobalClickClear, this);
				}
			},

			destroy: function () {

				if (typeof application != 'undefined') {
					application.off('global.click.clear', this.onGlobalClickClear, this);
				}

				if (this.parent && Class.Is(this.parent, Events)) {
					this.parent.off("destroy", this.onParentDestroy, this);
				}

				this.destroyEvents();

				this.parent = null;

				this.ParentCall();

				return this;
			},

			render: function (options) {

				options = Util.object(options);

				var template = this.setting("template", options["template"]);

				if (!template) {
					throw "Widget with no template";
				}

				var data = Util.object(options["data"]);

				data.widget = this;

				this.Parent({ html: UI.Template.render(template, data) });

				this.appended = true;

				if (options.afterRender !== false) {
					this.afterRender(options);
				}

				setTimeout(function () {
					this.initInteractionEvents();
				}.bind(this), 0);

				return this;
			},

			isAppended: function () {
				return this.appended;
			},

			initInteractionEvents: function ($elementHTML) {

				$elementHTML = $elementHTML || this.$element;

				if (!$elementHTML) {
					return;
				}

				this.destroyEvents($elementHTML);

				(function ($elementHTML, context){

					setTimeout(function () {

						this.initTouchClick($elementHTML);

						this.initInputFocusClick($elementHTML);

						//this.Class.initEvent("click", "on-click", this.$element, this);
						//this.Class.initEvent("mouseover", "on-mouseover", this.$element, this);
						//this.Class.initEvent("mouseout", "on-mouseout", this.$element, this);
						//this.Class.initEvent("change", "on-change", this.$element, this);
						this.Class.initEvent("keyup", "on-keyup", $elementHTML, this);
						this.Class.initEvent("keydown", "on-keydown", $elementHTML, this);
						this.Class.initEvent("input", "on-input", $elementHTML, this);
						this.Class.initEvent("focus", "on-focus", $elementHTML, this);
						this.Class.initEvent("blur", "on-blur", $elementHTML, this);
						//this.Class.initEvent("contextmenu", "on-contextmenu", this.$element, this);
						//this.Class.initEvent("touchmove", "on-touchmove", this.$element, this);
						//this.Class.initEvent("touchstart", "on-touchstart", this.$element, this);
						//this.Class.initEvent("touchend", "on-touchend", this.$element, this);

					}.bind(context), 25);

				})($elementHTML, this);
			},

			touchStart: {},

			onGlobalClickClear: function () {
				this.touchStart = {};
			},

			initInputFocusClick: function ($elementHTML) {

				$elementHTML = $elementHTML || this.$element;

				if (typeof this.addScrollOffset != 'function') {
					return;
				}

				/*$elementHTML.find('input, textarea').on('focus', function () {

					this.addScrollOffset();

				}.bind(this));*/
			},

			destroyEvents: function ($elementHTML) {

				$elementHTML = $elementHTML || this.$element;

				if (!$elementHTML) {
					return;
				}

				$elementHTML.off();

				this.removeInputFocusClick($elementHTML);

				this.removeTouchClick($elementHTML);
			},

			removeInputFocusClick: function ($elementHTML) {
				$elementHTML = $elementHTML || this.$element;

				$elementHTML.find('input').off();
			},

			removeTouchClick: function ($elementHTML) {
				$elementHTML = $elementHTML || this.$element;

				var selector = "[on-click]";

				$elementHTML.find(selector).off();
			},

			initTouchClick: function ($elementHTML) {
				$elementHTML = $elementHTML || this.$element;

				var selector = "[on-click]";

				var self = this;

				if (!$elementHTML) {
					return;
				}

				$elementHTML.find(selector).each(function () {

					var $el = $(this);

					var id = System.NumSeq.next('touchstart'+ self.template);

					$el.attr('data-touchid', id);

					$el.on('touchstart', function (caller) {

						var touchid = $(caller).data('touchid');

						self.touchStart[touchid] = (new Date().getTime());
					});

					$el.on('touchend', function (caller) {

						var touchid = $(caller).data('touchid');

						if (!self.touchStart[touchid]) {
							return;
						}

						var oldTime = self.touchStart[touchid];

						var currentTime = (new Date().getTime());

						var diff = currentTime - oldTime;

						if (diff > 1200) {
							return;
						}

						var handler = $(this).attr('on-click');

						if (Util.isFunction(self[handler])) {
							if (self[handler].call(self, this, caller) === false) {
								caller.stopPropagation();
							}
						}
					});
				});
			},

			override: function (fields, options) {
				var current = {};
				var i;

				for (i = 0; i < fields.length; i++) {
					current[fields[i]] = this[fields[i]];
				}

				options = Util.merge(current, options);

				for (i = 0; i < fields.length; i++) {
					this[fields[i]] = options[fields[i]];
				}
			},

			show: function (options, callback) {
				this.ParentCall();

				if (Util.isFunction(callback)) {
					callback();
				}
			},

			hide: function (options, callback) {
				this.ParentCall();

				if (Util.isFunction(callback)) {
					callback();
				}
			},

			onParentDestroy: function () {
				this.destroy();
			},

			getMainWidth: function (object) {
				return 0;
			},


			animatedOpenPage: false,
            animateTimeout: null,

			open: function (page, options) {

             	options = Util.object(options);

                var backMoveMode = options.backMoveMode ? options.backMoveMode : false;

				//ту же страницу не открываем еще раз
				if (this.openedPages[page] && this.currentOpenedPage == page) {

					this.openedPages[page].$element.attr('style', 'z-index:5;');

					return;
				}

				if (this.animatedOpenPage) {
					return;
				}

				var formClass = this.pages[page];

				var classOptions = {};

				if (Util.isArray(formClass)) {
					classOptions = Util.merge(formClass[1], options);
					formClass = formClass[0];
				}

				var nextZIndex = options['backClick'] ? 3 : 5;
				var currentZIndex = options['backClick'] ? 5 : 3;

				var nextFirstStyle = ['z-index: '+ nextZIndex +';'];
				var nextSecondStyle = nextFirstStyle.slice();

				var currentFirstStyle = ['z-index: ' + currentZIndex + ';'];
				var currentSecondStyle = currentFirstStyle.slice();

				if (options.animate) {

					var mainWidth = this.getMainWidth();

					if (options['backClick']) {

						currentFirstStyle.push('-moz-transform: translate3d(0, 0, 0);');
						currentFirstStyle.push('-webkit-transform: translate3d(0, 0, 0);');
						currentFirstStyle.push('transform: translate3d(0, 0, 0);');

						currentSecondStyle.push('-moz-transform: translate3d(' + mainWidth + 'px, 0, 0);');
						currentSecondStyle.push('-webkit-transform: translate3d(' + mainWidth + 'px, 0, 0);');
						currentSecondStyle.push('transform: translate3d(' + mainWidth + 'px, 0, 0);');

					} else {

						nextFirstStyle.push('-moz-transform: translate3d(' + mainWidth + 'px, 0, 0);');
						nextFirstStyle.push('-webkit-transform: translate3d(' + mainWidth + 'px, 0, 0);');
						nextFirstStyle.push('transform: translate3d(' + mainWidth + 'px, 0, 0);');

						nextSecondStyle.push('-moz-transform: translate3d(0, 0, 0);');
						nextSecondStyle.push('-webkit-transform: translate3d(0, 0, 0);');
						nextSecondStyle.push('transform: translate3d(0, 0, 0);');
					}
				}

				nextFirstStyle = nextFirstStyle.join('');
				nextSecondStyle = nextSecondStyle.join('');

				currentFirstStyle = currentFirstStyle.join('');
				currentSecondStyle = currentSecondStyle.join('');


				var currentPage = this.currentOpenedPage && this.openedPages[this.currentOpenedPage] ? this.openedPages[this.currentOpenedPage] : null;


				if (currentPage) {

					currentPage.$element.attr('style', currentFirstStyle);
				}

				if (!this.openedPages[page]) {

					this.openedPages[page] = Util.create(formClass, [this]).render(options);

					this.openedPages[page].$element.attr('style', nextFirstStyle);

					this.openedPages[page].appendTo(this.$page()).on("destroy", function () {
						delete this.openedPages[page];
					}.bind(this));

				} else {

					this.openedPages[page].$element.attr('style', nextFirstStyle);
				}

				var currentOpenedPage = options['backClick'] ? this.currentOpenedPage : null;

                if (!backMoveMode) {

                    Base.Hash.setHash(page, this.openedPages[page].hashLevel);

                    this.setHistoryPage(page);

                    this.currentOpenedPage = page;
                }


				if (options.animate) {

                    if (this.animateTimeout) {
                        clearTimeout(this.animateTimeout);
                        this.animateTimeout = null;
                    }

                    this.animateTimeout = setTimeout(function () {

                        if (this.animatedOpenPage) {
                            console.log('work timeout 100%');

                            this.animatedOpenPage = false;

                            this.open(this.currentOpenedPage);

                            //@parent
                            this.afterOpen(this.currentOpenedPage, options);

                            //@page class
                            this.openedPages[this.currentOpenedPage].afterOpen(this.currentOpenedPage, options);

                            var cbAnimateEnd = !backMoveMode ? this.hideOtherPages.bind(this) : function(){} ;

                            cbAnimateEnd(this.currentOpenedPage, currentOpenedPage);
                        }

                    }.bind(this), 600);

					var animateElement = this.openedPages[page].$element;

					var animateStyle = nextSecondStyle;

					if (options['backClick'] && currentPage) {

						animateElement = currentPage.$element;

						animateStyle = currentSecondStyle;
					}

                    var cbAnimateEnd = !backMoveMode ? this.hideOtherPages.bind(this) : function(){} ;

					(function(page, $element, style, callback, context, currentOpenedPage, options, pageclass) {

						setTimeout(function () {

							if ($element) {

								context.animatedOpenPage = true;

								$element.attr('style', style);

								$element.one($.support.transition.end, function () {

									callback(page, currentOpenedPage);

									//@parent
									context.afterOpen(page, options);

									//@page class
									pageclass.afterOpen(page, options);

									//@reinit scroll
									if (typeof pageclass.refreshIscroll == 'function') {
										pageclass.refreshIscroll();
									}

                                    context.animatedOpenPage = false;

                                    if (context.animateTimeout) {
                                        clearTimeout(context.animateTimeout);
                                        context.animateTimeout = null;
                                    }
								});
							}

						}, 100);

					})(page, animateElement, animateStyle, cbAnimateEnd, this, currentOpenedPage, options, this.openedPages[page]);

				} else if(!backMoveMode) {

					this.hideOtherPages(page, currentOpenedPage);

					//@parent
					this.afterOpen(page, options);

					//@page class
					this.openedPages[page].afterOpen(page, options);

					//@reinit scroll
					if (typeof this.openedPages[page].refreshIscroll == 'function') {
						this.openedPages[page].refreshIscroll();
					}
				}
			},

			afterOpen: function (page, options) {

			},


			hideOtherPages: function (nextPage, backOpenedPage) {

				for (var i in this.openedPages) if (this.openedPages.hasOwnProperty(i)) {

					var pageClass = this.openedPages[i];

					if (!pageClass.$element) {
						continue;
					}

					//if nextPage
					if (i == nextPage) {
                        pageClass.$element.attr('style', 'z-index: '+ pageClass.$element.css('zIndex')+';');
                        continue;
					}

					//если мы пошли назад
					if (i == backOpenedPage) {

						pageClass.destroy();

						continue;
					}

					if (pageClass.$element.is(':visible')) {

						//if need destroy
						var formClass = this.pages[i];

						var classOptions = {};

						if (Util.isArray(formClass)) {
							classOptions = formClass[1];
							formClass = formClass[0];
						}

						var needDestroy = classOptions && classOptions['destroyOnHide'] ? classOptions['destroyOnHide'] : false;

						if (needDestroy) {

							pageClass.destroy();

						} else {

							pageClass.$element.attr('style', 'z-index: ' + pageClass.$element.css('zIndex') + '; display: none;');
						}
					}
				}

			},

			setHistoryPage: function (page) {

				var pages = LocalStorage.get('history.pages');

				if (pages && Util.isArray(pages)) {

					if (pages.length > 200) {

						pages = [];
					}

				} else {

					pages = [];
				}

				var item = pages.slice(-1)[0];

				if (item == page) {

					return;
				}

				pages.push(page);

				LocalStorage.set('history.pages', pages);
			},

			clearHistoryPages: function (pages) {

				var hpages = LocalStorage.get('history.pages');

				var clearPages = [];

				if (hpages && Util.isArray(hpages)) {

					for (var i in hpages) if (hpages.hasOwnProperty(i)) {

						var page = hpages[i];

						if (!~pages.indexOf(page)) {
							clearPages.push(page)
						}

					}

				}

				LocalStorage.set('history.pages', clearPages);
			},

			backOpenPage: function (returnPageName, options, pagesList) {

                options = Util.object(options);

				var pages = pagesList ? pagesList : LocalStorage.get('history.pages');

                if (!Util.isArray(pages)) {
                    return;
                }

				var last = pages.splice(-1, 1)[0];

                if (!returnPageName) {

                    LocalStorage.set('history.pages', pages);
                }


				if (last == this.currentOpenedPage) {

					return this.backOpenPage(returnPageName, options, pages.slice());
				}

                if (!returnPageName) {

                    var pageName = last ? last : this.defaultPage;

                } else {

                    var pageName = last ? last : null;
                }

				if (pageName && !this.pages[pageName]) {
					return this.backOpenPage(returnPageName, options, pages.slice());
				}

				if (returnPageName) {

                    return pageName;
				}

				this.open(pageName, Util.merge({backClick: true, animate: true}, options, true));
			},

			$page: function () {
				return this.$element;
			},

			openNext: function (options) {

				if (this.defaultPage && this.pages[this.defaultPage]) {
					this.open(this.nextPage(options), options);
				}
			},


			afterRender: function () {

			},

			hash: function () {

			},

			nextPage: function (options) {

				var openCurrent = true;

				if (options && typeof options['openCurrent'] !== 'undefined') {
					openCurrent = options['openCurrent'];
				}

				if (openCurrent && this.currentOpenedPage && this.openedPages[this.currentOpenedPage]) {
					return this.currentOpenedPage;
				}

				var page = null;

				if (openCurrent) {
					page = Base.Hash.getHash(this.hashLevel + 1);
				}

				if (!page) {
					return this.defaultPage;
				}

				if (this.pages[page]) {
					return page;
				}

				return this.defaultPage;
			},

			initCSS: function (options) {

			},

			hidePages: function (nothidepage, options) {
				var self = this;

				var d = $.Deferred();

				var deferreds = [];

				for (var page in this.openedPages) if (this.openedPages.hasOwnProperty(page)) {

					var formClass = this.pages[page];
					var classOptions = {};

					if (Util.isArray(formClass)) {
						classOptions = formClass[1];
						formClass = formClass[0];
					}

					if (page !== nothidepage) {
						this.emit("Page.Hide", {page: page});

						if (classOptions && classOptions.destroyOnHide) {

							(function (pageName) {

								var destroyDeffered = self.close(page, options);

								deferreds.push(destroyDeffered);

								$.when(destroyDeffered).done(function () {

									self.openedPages[pageName].destroy();
								});

							})(page);

						} else {

							deferreds.push(this.close(page, options));
						}
					}
				}

				$.when.apply($, deferreds).done(function () {
					d.resolve();
				});

				return d;
			},

			close: function (page, options) {
				var d = $.Deferred();

				if (typeof this.openedPages[page] == 'undefined') {
					return d.resolve();
				}

				this.openedPages[page].hide(options, function () {

					d.resolve();
				});

				return d;
			},

			destroyPage: function (page, options) {

				if (!this.openedPages[page]) {
					return;
				}

				this.openedPages[page].destroy();
			},

			showWidget: function (className, options) {
				var self = this;

				var needDestroy = options && typeof options.needDestroy != 'undefined' ? options.needDestroy : true;

				if (this.widgets[className] && needDestroy) {
					this.widgets[className].destroy();
					delete this.widgets[className];
				}

				this.widgets[className] = Util.create(className, [this, options]).render();

				this.widgets[className].initCSS(options);

				var $element = (options && options.prependElement) ? options.prependElement : $('#main-container');

				this.widgets[className].prependTo($element).on("destroy", function () {
					delete self.widgets[className];
				});

				this.widgets[className].show(options, function () {

					if (options && options.callback) {
						options.callback();
					}

				});

				return this.widgets[className];
			},

			setArgumentString: function (string) {
				Base.Hash.setHash(string, this.hashLevel + 1);
			},

			getArgumentString: function () {
				return Base.Hash.getHash(this.hashLevel + 1);
			}

		});

		KrujevaMobile.Page.Static({

			lastOpened: {},

			setLastOpened: function (index, name) {

				for (var i in this.lastOpened) if (this.lastOpened.hasOwnProperty(i)) {

					if (i > index) {
						delete this.lastOpened[i];
					}
				}

				this.lastOpened[index] = name;
			},

			getLastOpened: function (index) {
				return this.lastOpened[index];
			}
		});
	});