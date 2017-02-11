exports.module = {
	listeners: {},

	on: function(event, object, method){

		if (!this.listeners[event]) {
			this.listeners[event] = [];
		}

		for(var i in this.listeners[event]) if(this.listeners[event].hasOwnProperty(i)){
			var listener = this.listeners[event][i];

			if (listener["object"] == object) {
				return;
			}
		}

		this.listeners[event].push({
			"object": object,
			"method": method
		});
	},

	off: function(event, object){

		if (!this.listeners[event]) {
			return true;
		}

		for (var i in this.listeners[event]) if (this.listeners[event].hasOwnProperty(i)) {
			var listener = this.listeners[event][i];

			if (listener["object"] == object) {
				this.listeners[event].splice(i, 1);
				return;
			}
		}
	},

	emit: function(event, args){
		if (!this.listeners[event]) {
			return;
		}

		for (var i in this.listeners[event]) if (this.listeners[event].hasOwnProperty(i)) {
			var listener = this.listeners[event][i];

			listener.method.call(listener.object, args);
		}
	}
};