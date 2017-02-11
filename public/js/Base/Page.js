Module.define("UI.Element", "UI.Template", "Base.Hash",

	function () {
		NS("Base");

		Base.Page = Class(UI.Element, {
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
				if (parent && this.Is(Base.Page)) {

					if (typeof parent.hashLevel !== 'undefined') {
						this.hashLevel = parent.hashLevel + 1;
					}
				}
			},

			destroy: function () {

				if (this.parent && Class.Is(this.parent, Events)) {
					this.parent.off("destroy", this.onParentDestroy, this);
				}

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

			initInteractionEvents: function () {

				this.destroyEvents(this.$element);

				this.Class.initEvent("click", "on-click", this.$element, this);
				this.Class.initEvent("mouseover", "on-mouseover", this.$element, this);
				this.Class.initEvent("mouseout", "on-mouseout", this.$element, this);
				this.Class.initEvent("change", "on-change", this.$element, this);
				this.Class.initEvent("keyup", "on-keyup", this.$element, this);
				this.Class.initEvent("keydown", "on-keydown", this.$element, this);
				this.Class.initEvent("input", "on-input", this.$element, this);
				this.Class.initEvent("touchmove", "on-touchmove", this.$element, this);
				this.Class.initEvent("contextmenu", "on-contextmenu", this.$element, this);
				this.Class.initEvent("touchstart", "on-touchstart", this.$element, this);
				this.Class.initEvent("touchend", "on-touchend", this.$element, this);
			},

			destroyEvents: function ($elementHTML) {

				$elementHTML = $elementHTML || this.$element;

				if (!$elementHTML) {
					return;
				}

				$elementHTML.off();
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

			open: function (page, options) {

				options = Util.object(options);

				if (typeof options.parentHashLevel == 'undefined') {
					options.parentHashLevel = this.hashLevel;
				}

				var formClass = this.pages[page];

				var classOptions = {};

				if (Util.isArray(formClass)) {
					classOptions = Util.merge(formClass[1], options);
					formClass = formClass[0];
				}

				var hidePages = (typeof classOptions.hidePages == 'undefined') ? true : classOptions.hidePages;

				var deferred = $.Deferred();

				if (hidePages) {

					deferred = this.hidePages(page, options);

				} else {

					deferred.resolve();
				}

				$.when(deferred).done(function () {

					function open(pageClass, options, page, afterRender) {

						if (!options.openedPages) {
							options.openedPages = [];
						}

						options.openedPages.push({
							level: pageClass.hashLevel,
							page: page,
							afterRender: ~~afterRender
						});

						//on all Render or Open
						if (self.hashLevel === options.parentHashLevel) {

							self.recursiveAfterRender(options);
						}
					}

					var self = this;

					this.currentOpenedPage = page;

					if (this.openedPages[page]) {

						var lastOpened = Base.Page.getLastOpened(this.openedPages[page]['hashLevel']);

						//повторно открываем эту страницу - надо идти по дефолту
						if (page == lastOpened) {
							options['openCurrent'] = false;
						}

						this.openedPages[page].openNext(options);

						open(this.openedPages[page], options, page);

					} else {

						options = Util.merge(options, {afterRender: false});

						this.openedPages[page] = Util.create(formClass, [this]).render(options);

						this.openedPages[page].openNext(options);

						this.openedPages[page].initCSS(options);

						this.openedPages[page].appendTo(this.$page()).on("destroy", function () {
								delete this.openedPages[page];
							}.bind(this));

						open(this.openedPages[page], options, page, true);
					}

				}.bind(this));

				return deferred;
			},

			$page: function () {
				return this.$element;
			},

			openNext: function (options) {

				if (this.defaultPage && this.pages[this.defaultPage]) {
					this.open(this.nextPage(options), options);
				}
			},

			recursiveAfterRender: function (options) {

				var pages = Util.sort(options.openedPages, 'level');

				var classPage = null;

				var parentClass = null;

				for (var i in pages) if (pages.hasOwnProperty(i)) {

					var page = pages[i];

					if (!parentClass) {
						parentClass = this;
					} else {
						parentClass = classPage;
					}

					if (!classPage) {
						classPage = this.openedPages[page.page];
					} else {
						classPage = classPage.openedPages[page.page];
					}

					var formClass = this.pages[page.page];

					var classOptions = {};

					if (Util.isArray(formClass)) {
						classOptions = Util.merge(formClass[1], options);
						formClass = formClass[0];
					}

					var setHash = (typeof classOptions.setHash == 'undefined') ? true : classOptions.setHash;

					if (setHash) {

						Base.Hash.setHash(page.page, page.level);
					}

					if (classPage) {

						Base.Page.setLastOpened(page.level, page.page);

						classPage.$element.show();

						classPage.show(options);

						classPage.emit("show");

						if (page.afterRender) {

							classPage.afterRender(options);
						}

						parentClass.emit("Page.Show", {page: page.page});
					}
				}

				classPage = null;
				parentClass = null;
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

		Base.Page.Static({

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