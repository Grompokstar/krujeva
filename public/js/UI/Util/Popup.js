Module.define(
	function () {
		NS("UI.Util");

		UI.Util.Popup = Static({
			hovers: [],
			clicks: [],

			initialize: function () {
				this.initHovers();
				this.initClicks();
			},

			initHovers: function () {
				var self = this;

				$(window).on("mousemove", function (args) {
					for (var i = self.hovers.length - 1; i >= 0; i--) {
						var item = self.hovers[i];
						var element = args.target;
						var found = false;
						var elements = item.elements;
						var callback = item.callback;

						while (element) {
							for (var k = 0; k < elements.length; k++) {
								if (element == elements[k]) {
									found = true;
									break;
								}
							}

							if (found) {
								break;
							}

							element = element.parentElement;
						}

						if (!found) {
							if (Util.isFunction(callback)) {
								self.hovers.splice(i, 1);
								callback();
							}
						}
					}
				});
			},

			initClicks: function () {
				var self = this;

				$(window).on("click", function (args) {
					for (var i = self.clicks.length - 1; i >= 0; i--) {
						var item = self.clicks[i];
						var element = args.target;
						var found = false;
						var elements = item.elements;
						var callback = item.callback;

						while (element) {
							for (var k = 0; k < elements.length; k++) {
								if (element == elements[k]) {
									found = true;
									break;
								}
							}

							if (found) {
								break;
							}

							element = element.parentElement;
						}

						if (!found) {
							if (Util.isFunction(callback)) {
								self.clicks.splice(i, 1);
								callback();
							}
						}
					}
				});
			},

			createHover: function () {
				if (arguments.length < 2) {
					return;
				}

				var callback = Array.prototype.pop.call(arguments);
				var elements = [];

				Util.each(arguments, function ($element) {
					$element = $($element);

					for (var i = 0; i < $element.length; i++) {
						var element = $element[i];
						elements.push(element);
					}
				});

				var hover = {
					elements: elements,
					callback: callback
				};

				this.hovers.push(hover);

				return hover;
			},

			destroyHover: function (hover) {
				var index = this.hovers.indexOf(hover);

				if (~index) {
					this.hovers.splice(index, 1);
				}

				return null;
			},

			createClick: function () {
				if (arguments.length < 2) {
					return;
				}

				var callback = Array.prototype.pop.call(arguments);
				var elements = [];

				Util.each(arguments, function ($element) {
					$element = $($element);

					for (var i = 0; i < $element.length; i++) {
						var element = $element[i];
						elements.push(element);
					}
				});

				var click = {
					elements: elements,
					callback: callback
				};

				this.clicks.push(click);

				return click;
			},

			destroyClick: function (click) {
				var index = this.clicks.indexOf(click);

				if (~index) {
					this.clicks.splice(index, 1);
				}

				return null;
			}
		});
	}
);
