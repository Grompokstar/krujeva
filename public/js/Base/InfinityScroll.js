Module.define(

	function () {
		NS("Base");

		Base.InfinityScroll = Class({
			params: null,
			sender: false,

			/*
			 *  params - Object {} key: value
			 *
			 *  lowerLimit = Number:10 - граница при котором возникает событие в px
			 *  event = function: function(callback){callback();} //функция которая будет вызываться когда закончится скролл
			 * */
			initialize: function (el, params) {
				this.params = params || null;

				if (el) {
					this.initEvent(el);
				}
			},

			initEvent: function (element) {
				var eventLimit = parseInt(this.params.lowerLimit) || 10;
				var self = this;

				if (!this.params.event) {
					return;
				}

				var object =  this.params.event["object"] || null;
				var method =  this.params.event["method"] || null;

				if (!object) {
					return;
				}

				object.on("destroy", function () {
					this.params = null;
					this.sender = false;
				}.bind(this));

				element.scroll(function (caller) {

					var el = caller.target;
					var scrollTop = element.scrollTop() || 0;
					var elHeight = el.scrollHeight - element.height();

					if (eventLimit >= (elHeight - scrollTop) && !self.sender) {
						self.sender = true;

						method.call(object, function () {
							self.sender = false;
						}, element, {scrollTop: scrollTop});
					}
				});

			}
		});
	});
