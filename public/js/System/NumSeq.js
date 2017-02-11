Module.define(

	function () {
		NS("System");

		System.NumSeq = Static({
			names: {},

			next: function (name) {
				if (!this.names[name]) {
					this.names[name] = 0;
				}

				return ++this.names[name];
			}
		});
	}
);
