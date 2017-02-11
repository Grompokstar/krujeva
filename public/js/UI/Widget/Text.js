Module.define(
	"UI.Widget.Base",

	function () {

	NS("UI.Widget");

	UI.Widget.Text = Class(UI.Widget.Base, {
		liveChangeTimeout: 400,

		val: function (val) {
			if (val !== undefined) {
				if (this.$element) {
					this.$element.val(val);
				}

				return this;
			} else {
				return this.$element ? this.$element.val() : null;
			}
		},

		liveChange: function (on, timeout) {
			var self = this;

			on = Util.coalesce(on, true);

			if (!this.liveChangeKeyDownHandler) {
				this.liveChangeKeyDownHandler = function () {
					if (self.keyDownFirstStroke) {
						self.keyDownValue = this.value;
						self.keyDownFirstStroke = false;
					}
				};
			}

			timeout = Util.coalesce(timeout, this.liveChangeTimeout);

			if (!this.liveChangeKeyUpHandler) {
				this.liveChangeKeyUpHandler = function () {
					Util.delayed(timeout, self.onLiveChangeKeyUp, self);
				};
			}

			if (!this.liveChangeOn && on) {
				this.$element.on("keydown", this.liveChangeKeyDownHandler);
				this.$element.on("keyup", this.liveChangeKeyUpHandler);
				this.keyDownFirstStroke = true;
			}

			if (this.liveChangeOn && !on) {
				this.$element.off("keydown", this.liveChangeKeyDownHandler);
				this.$element.off("keyup", this.liveChangeKeyUpHandler);
			}

			this.liveChangeOn = on;

			return this;
		},

		onLiveChangeKeyUp: function () {
			this.keyDownFirstStroke = true;

			if (this.element.value != this.keyDownValue && !this.isDestroyed) {
				this.emit("livechange", { value: this.element.value });
			}
		}
	});
});
