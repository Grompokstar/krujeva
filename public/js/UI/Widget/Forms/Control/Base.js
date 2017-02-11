Module.define(
	"Events",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Base = Class(Events, {
			parent: null,
			$control: null,
			control: null,
			target: null,

			initialize: function (parent, $control) {
				$control = $($control);

				this.parent = parent;
				this.$control = $control;
				this.control = $control[0];
				this.target = $control.attr("target");
			},

			bind: function () {
				this.emit("bind");
			},

			unbind: function () {
				this.emit("unbind");
			},

			update: function () {
				this.emit("update");
			},

			parseTarget: function (target) {
				if (!target) {
					target = this.target;
				}

				var regExp = new RegExp("\\[([^\\]]+)\\]", "g");
				var match = null;

				while (match = regExp.exec(target)) {
					var group = match[1];
					var index = this.groupIndex(group);

					if (~index) {
						target = target.replace("[" + group + "]", "[" + index + "]");
					}
				}

				return target;
			},

			getValue: function () {
				var value = null;

				try {
					var target = this.parseTarget();

					eval("value = this.parent." + target + ";");
				} catch (e) {
					console.log("Control.getValue() failed", target, e);
				}

				return value;
			},

			setValue: function (value) {
				var args = {
					control: this,
					value: value,
					update: false
				};

				try {
					if (this.parent && Util.isFunction(this.parent.onControlPreSetValue)) {
						this.parent.onControlPreSetValue(args);
					}

					var target = this.parseTarget();

					eval("this.parent." + target + " = args.value;");

					if (this.parent && Util.isFunction(this.parent.onControlSetValue)) {
						this.parent.onControlSetValue(args);
					}

					if (args.update) {
						this.update();
					}
				} catch (e) {
					console.log("Control.setValue() failed", target, value);
					console.dir(e);
				}

				return value;
			},

			groupIndex: function (group) {
				var selector = "[control-group=\"" + group + "\"]";
				var $group = this.$control.parentsUntil(selector).last().parent();

				if (!$group.length) {
					return -1;
				}

				return $group.parent().children().index($group);
			}
		});
	}
);
