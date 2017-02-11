Module.define(
	function () {

		NS("System");

		System.LongOperation = Class({
			keys: [],
			keyIdx: null,
			itemTimeout: null,
			timeout: 0,
			callback: null,
			collection: null,
			operation: null,

			initialize: function (collection, operation, timeout) {
				this.timeout = Util.coalesce(timeout, 0);
				this.collection = collection;
				this.operation = operation;
			},

			run: function (callback) {
				this.keys = [];
				this.keyIdx = 0;

				callback = Util.coalesce(callback);

				this.callback = callback;

				for (var idx in this.collection) {
					if (this.collection.hasOwnProperty(idx)) {
						this.keys.push(idx);
					}
				}

				this.runItem();
			},

			stop: function () {
				var self = this;

				if (this.itemTimeout) {
					clearTimeout(this.itemTimeout);
					this.itemTimeout = null;
				}

				this.keyIdx = 0;

				if (this.callback) {
					this.callback();
					this.keys = null;
					this.collection = null;
					this.operation = null;
					this.keys = [];
				}
			},

			runItem: function () {
				var self = this;

				if (this.keyIdx == this.keys.length) {
					this.itemTimeout = null;

					if (this.callback) {
						this.callback();
						this.keys = null;
						this.collection = null;
						this.operation = null;
						this.keys = [];
					}
					return;
				}

				if (this.operation(this.collection[this.keys[this.keyIdx]], this.keys[this.keyIdx]) === false) {
					this.keyIdx = this.keys.length;
					this.runItem();
				} else {
					this.keyIdx++;
					this.itemTimeout = setTimeout(function () {
						self.runItem();
					}, this.timeout);
				}
			}
		});
	}
);