Module.define(

	function () {
		NS("Base");

		Base.Hash = Static({

			oldHash: null,
			
			initialize: function () {
				this.getHash();
			},

			getHash: function (index) {
				index = index || 0;

				var hash = window.location.hash.replace('#', '').split('/');

				if (!this.oldHash) {
					this.oldHash = hash;
				}

				var page = null;

				if (typeof this.oldHash[index] !== 'undefined') {
					page = this.oldHash[index];
				}

				if (typeof hash[index] !== 'undefined') {
					page = hash[index];
				}

				return page;
			},

			setHash: function (page, index) {
				index = index || 0;

				var hash = window.location.hash.replace('#', '').split('/');

				hash = hash.slice(0, index);

				hash.push(page);

				window.location.hash = hash.join('/');

				this.oldHash = window.location.hash.replace('#', '').split('/');
			}
		});
	});