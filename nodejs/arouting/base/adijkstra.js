exports.module = function () {

	this.graph = {}; //связный взвешенный граф (циклический)
	this.startId = {};  //точка от куда ищем
	this.endId = {}; //точка куда нужно подъехать

	this.minimumNode = {}; //храним сторону, точку и ее минимум
	this.visitedWays = {}; //храним здесь посещенные пути для каждой из ребер + храним все возможные пути из этого ребра
	this.route = {}; //машрут следования
	this.sumsPaths = {}; //будем хранить суммы до путей
	this.noSenseWays = {}; //пути в которые нет смысла идти
	this.visitedWay = {}; //посещенный путь + безсмысленный путь

	this.queueWays = []; //очередь для путей - которые нужно обработать
	this.queueIndex = 0; //индекс текущего минимума

	this.iterationIndex = 0;

	this.callback = null;

	this.destroy = function () {

		this.graph = {};
		this.startId = {};
		this.endId = {};

		this.minimumNode = {};
		this.visitedWays = {};
		this.route = {};
		this.sumsPaths = {};
		this.noSenseWays = {};
		this.visitedWay = {};

		this.queueWays = [];
		this.queueIndex = 0;

		this.iterationIndex = 0;

		this.callback = null;
	};

	this.init = function (graph, startId, endId, callback) {

		this.graph = graph;

		this.startId = startId;

		this.endId = endId;

		this.callback = callback;

		this.startMicrotime = new Date().getTime();

		this.iteration(this.startId);
	};

	this.finish = function () {

		var endMicrotime = new Date().getTime() - this.startMicrotime;

		if (!this.minimumNode[this.endId]) {

			console.log('iterationIndex => ' + this.iterationIndex, endMicrotime);

			console.log('no route');

			return null;
		}

		var distance = this.minimumNode[this.endId]['distance'];

		var endId = this.minimumNode[this.endId]['path'];

		var path = [];

		while (true) {
			var node = this.graph[this.clear(endId)];

			path.push(node['geog']);

			if (this.clear(endId) == this.startId) {
				break;
			}

			endId = this.route[endId];
		}

		path.reverse();

		console.log(distance);
		console.log('iterationIndex => ' + this.iterationIndex, endMicrotime);

		this.callback({'distance': distance, 'path': path});

		this.destroy();
	};

	this.makeVisited = function (beforeId, currentId, nextId, beforeWay, currentWay, nextWay) {

		if (beforeId ==6 && currentId == 7 && nextId == 8) {
			return false;
		}

		return true;
	};

	this.clear = function (id, index) {

		index = typeof index == 'undefined' ? 1 : index;

		return (id + '').split('_')[index];
	};

	this.iteration = function (currentNodeId, beforeNodeId, beforeWayId) {

		beforeNodeId = typeof beforeNodeId == 'undefined' ? null : beforeNodeId;

		beforeWayId = typeof beforeWayId == 'undefined' ? null : beforeWayId;

		this.iterationIndex++;

		var endLength = this.minimumNode[this.endId] ? this.minimumNode[this.endId]['distance'] : null;

		if (beforeNodeId === null) {

			this.minimumNode[currentNodeId] = {
				'distance' : 0,
				'path' : '_'+ currentNodeId
			};
		}

		beforeNodeId = beforeNodeId === null ? '' : beforeNodeId;

		//beforeWayId


		//currentNodeId

		var currentWayId = beforeNodeId ? this.graph[beforeNodeId]['ways'][currentNodeId]['way'] : null;

		var beforePath = beforeNodeId + '_' + currentNodeId;

		var beforeDistance = this.sumsPaths[beforePath] ? this.sumsPaths[beforePath] : 0;

		//найдем все возможные пути для этой точки
		var nodes = this.graph[currentNodeId]['ways'];

		for (var i in nodes) if (nodes.hasOwnProperty(i)) {

			var nextNode = nodes[i];

			var nextNodeId = nextNode['id'];

			var nextWayId = nextNode['way'];

			var nextPath = currentNodeId + '_' + nextNodeId;

			//0.1 посчитаем дистанцию до этого пути
			var nextDistance = nextNode['distance'] + beforeDistance;

			//0 - мы уже в этот путь ходили - больше не пойдем - если только сумма наша не уменьшится
			if (this.visitedWays[beforePath] && this.visitedWays[beforePath]['visitedpaths'][nextPath] && (!this.minimumNode[nextNodeId] || this.minimumNode[nextNodeId]['distance'] <= nextDistance)) {
				continue;
			}

			//1 можем ли мы пойти в следующий путь (линию) из этого пути
			if (!this.makeVisited(beforeNodeId, currentNodeId, nextNodeId, beforeWayId, currentWayId, nextWayId)) {
				continue;
			}

			//2 добавим что для этого пути (beforePath) - есть этот путь (nextPath)
			if (!this.visitedWays[beforePath]) {

				this.visitedWays[beforePath] = {
					'paths': {},
					'visitedpaths': {}
				};
			}

			this.visitedWays[beforePath]['paths'][nextPath] = true;

			//3 проверим стоит ли идти в эту точку
			var needMin = true;

			var minNodePath = false;

			if (this.minimumNode[nextNodeId] && this.minimumNode[nextNodeId]['distance'] <= nextDistance && this.minimumNode[nextNodeId]['path'] != nextPath) {
				//нет новых путей - нет смысла туда идти - говорим что мы этот путь якобы прошли и ищем дальше
				if (!this.issetNewPaths(nextNodeId, currentNodeId, (beforeNodeId ? this.graph[beforeNodeId]['ways'][currentNodeId]['way'] : null), this.minimumNode[nextNodeId]['path'])) {

					this.visitedWays[beforePath]['visitedpaths'][nextPath] = true;

					this.visitedWay[nextPath] = true;

					this.noSenseWays[nextPath] = true;

					needMin = false;

					//есть новые пути
				} else {

					minNodePath = true;
				}
			}

			//4 запишем в очередь
			if (needMin && (endLength === null || endLength >= nextDistance)) {

				this.queueWays.push(nextPath);

				if (minNodePath) {

					this.minimumNode[nextNodeId] = {
						'distance': nextDistance, 'path': nextPath
					};

					this.route[nextPath] = beforePath;
				}

				//5 если наша дистанция больше - чем возможная до конечной точки - значит дальше идти нет смысла
			} else if (endLength !== null && endLength < nextDistance) {

				this.visitedWays[beforePath]['visitedpaths'][nextPath] = true;

				this.visitedWay[nextPath] = true;

				this.noSenseWays[nextPath] = true;
			}

			if (!this.sumsPaths[nextPath] || this.sumsPaths[nextPath] > nextDistance) {

				this.sumsPaths[nextPath] = nextDistance;
			}

			//6 далее считаем минимумы
			//6.1 проверим минимум возможно уже есть для этой точки - тогда запишем в альтернативный путь
			if (!this.minimumNode[nextNodeId] || this.minimumNode[nextNodeId]['distance'] > nextDistance) {

				this.minimumNode[nextNodeId] = {
					'distance': nextDistance, 'path': nextPath
				};

				this.route[nextPath] = beforePath;

				endLength = this.minimumNode[this.endId] ? this.minimumNode[this.endId]['distance'] : null;
			}

			//6.2 есть минимум но этот оказался мешьше
			if (this.minimumNode[nextNodeId] && this.minimumNode[nextNodeId]['distance'] > nextDistance) {

				//надо убрать все возможные пути - где говорится что нет смысла - теперь из этой точки есть смысл снова все обойти - все станет дешевле теперь
				this.clearNoSensePaths(nextNodeId, currentNodeId, (beforeNodeId ? this.graph[beforeNodeId]['ways'][currentNodeId]['way'] : null));
			}
		}

		//7 возьмем минимум из очереди
		nextPath = this.getNextPath();

		if (nextPath === null) {

			this.finish();

			return;

		} else {

			var bp = this.route[nextPath] ? this.route[nextPath] : null;

			if (bp !== null) {

				this.visitedWays[bp]['visitedpaths'][nextPath] = true;

				this.visitedWay[nextPath] = true;
			}

			var iterationMinNodeId = this.clear(nextPath);

			currentNodeId = bp ? this.clear(bp) : null;

			beforeNodeId = this.route[bp] ? this.clear(this.route[bp]) : null;
		}

		setImmediate(function () {

			this.iteration(iterationMinNodeId, currentNodeId, (beforeNodeId ? this.graph[beforeNodeId]['ways'][currentNodeId]['way'] : null));

		}.bind(this));
	};

	this.getNextPath = function () {

		if (!this.queueWays[this.queueIndex]) {
			return null;
		}

		var path = this.queueWays[this.queueIndex];

		var endLength = this.minimumNode[this.endId] ? this.minimumNode[this.endId]['distance'] : null;

		if (!this.sumsPaths[path]) {
			this.queueIndex++;
			return this.getNextPath();
		}

		var distance = this.sumsPaths[path];

		if
		(
			this.visitedWays[path] &&
			Object.keys(this.visitedWays[path]['paths']).length == Object.keys(this.visitedWays[path]['visitedpaths']).length
		)
		{
			this.queueIndex++;
			return this.getNextPath();
		}

		if (this.noSenseWays[path]) {
			this.queueIndex++;
			return this.getNextPath();
		}

		if (!this.route[path]) {
			this.queueIndex++;
			return this.getNextPath();
		}

		if (endLength === null || endLength > distance) {
			this.queueIndex++;
			return path;
		}

		return null;
	};

	this.clearNoSensePaths = function (currentNodeId, beforeNodeId, beforeWayId) {

		var currentWayId = this.graph[beforeNodeId]['ways'][currentNodeId]['way'];

		if (this.visitedWays[beforeNodeId + '_' + currentNodeId]) {

			this.visitedWays[beforeNodeId + '_' + currentNodeId]['visitedpaths'] = {};
		}

		var nodes = this.graph[currentNodeId]['ways'];

		for (var i in nodes) if (nodes.hasOwnProperty(i)) {

			var nextNode = nodes[i];

			var nextNodeId = nextNode['id'];

			var nextPath = currentNodeId + '_' + nextNodeId;

			var nextWayId = nextNode['way'];

			if (!this.makeVisited(beforeNodeId, currentNodeId, nextNodeId, beforeWayId, currentWayId, nextWayId)) {
				continue;
			}

			delete this.noSenseWays[nextPath];
			delete this.visitedWay[nextPath];
		}
	};

	this.issetNewPaths = function (currentNodeId, beforeNodeId, beforeWayId, minimumPath) {

		var issetNew = false;

		var currentWayId = this.graph[beforeNodeId]['ways'][currentNodeId]['way'];

		//1 собираем возможные пути для $minimumPath
		var fromId = this.clear(minimumPath, 0);

		var toId = this.clear(minimumPath);

		var cWayId = fromId ? this.graph[fromId]['ways'][toId]['way'] : null;

		var pB = this.route[minimumPath] ? this.route[minimumPath] : null;

		var bNodeId = this.route[pB] ?  this.clear(this.route[pB]) : null;

		var bWayId = bNodeId ? this.graph[bNodeId]['ways'][fromId]['way'] : null;

		var availableWays = {};

		var nodes = this.graph[toId]['ways'];

		var nextNode, nextNodeId, nextPath, nextWayId;

		for (var i in nodes) if (nodes.hasOwnProperty(i)) {

			nextNode = nodes[i];

			nextNodeId = nextNode['id'];

			nextPath = toId + '_' + nextNodeId;

			nextWayId = nextNode['way'];

			if (!this.makeVisited(fromId, toId, nextNodeId, bWayId, cWayId, nextWayId)) {
				continue;
			}

			availableWays[nextPath] = true;
		}

		//2 проверяем пути
		nodes = this.graph[currentNodeId]['ways'];

		for (i in nodes) if (nodes.hasOwnProperty(i)) {

			nextNode = nodes[i];

			nextNodeId = nextNode['id'];

			nextPath = currentNodeId + '_' + nextNodeId;

			nextWayId = nextNode['way'];

			if (!this.makeVisited(beforeNodeId, currentNodeId, nextNodeId, beforeWayId, currentWayId, nextWayId)) {
				continue;
			}

			//2 путь уже посещен
			if (this.visitedWay[nextPath]) {
				continue;
			}

			if (!availableWays[nextPath]) {

				issetNew = true;

				break;
			}
		}

		return issetNew;
	}

};