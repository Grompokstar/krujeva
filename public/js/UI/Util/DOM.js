Module.define(
	function () {
		NS("UI.Util");

		UI.Util.DOM = Static({
			isParent: function (child, parent) {
				var element = child;

				while (element) {
					if (element == parent) {
						return true;
					}

					element = element.parentElement;
				}

				return false;
			}
		});
	}
);
