Module.define(

	function () {
		NS("KrujevaMobile");

		KrujevaMobile.AnimationSync = Static({

			isAnimated: false,

			makeClick: function () {
				return !this.isAnimated;
			},

			startAnimation: function () {
				this.isAnimated = true;
			},

			endAnimation: function () {
				this.isAnimated = false;
			}
		})
	}
);