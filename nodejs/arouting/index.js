global.absolutePath = __dirname;
require('./base/core');

var http = require('http');

var url = require('url');

var adijkstra = require('./base/adijkstra')['module'];

var graph = {};

function listenServer() {

	console.log('start listen port '+ Config.port);

	http.createServer(function (req, res) {

		var url_parts = url.parse(req.url, true);

		onResClose(res, function () {
			console.log('close');
		});

		readArguments(url_parts.query, function (args) {

			var d = new adijkstra();

			d.init(graph, args.startId, args.endId, function (result) {

				res.setHeader('Access-Control-Allow-Origin', '*');

				res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

				res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');

				res.setHeader('Access-Control-Allow-Credentials', true);

				res.setHeader('Content-Type', 'application/json');

				res.end(JSON.stringify(result));

			});
		});


	}).listen(Config.port);
}

function readArguments(query, callback) {

	var from = query['from[]'];

	var to = query['to[]'];

	var wait = 0;

	var args = {};

	function finish () {
		wait--;

		if (!wait) {
			callback(args);
		}
	}

	wait++;
	nearest(from[1], from[0], function (result) {

		args['startId'] = result;

		finish();
	});

	wait++;
	nearest(to[1], to[0], function (result) {

		args['endId'] = result;

		finish();
	});
}

function nearest(lat, lng, callback) {
	return queryScalar("select id from routegraph order by geog <-> 'POINT(" + lat + " " + lng + ")' limit 1", [], callback);
}

function loadGraph1() {

	function rib (id, rib) {

		rib[id] = {
			'id' : id,
			'ways' : {},
			'geog' : 'ss'
		};
	}

	function way (rib, fromid, id, way, distance) {

		rib[fromid]['ways'][id] = {
			'id' : id,
			'way': way,
			'distance': distance
		};
	}

	function init() {

		var ribs = {};
		rib(1, ribs);
		way(ribs, 1, 8, 'li0', 1);

		rib(2, ribs);
		way(ribs, 2, 3, 'li0', 2);
		way(ribs, 2, 7, 'li0', 3);

		rib(3, ribs);
		way(ribs, 3, 2, 'li0', 2);
		way(ribs, 3, 4, 'li0', 1);

		rib(4, ribs);
		way(ribs, 4, 3, 'li0', 1);
		way(ribs, 4, 5, 'li0', 2);
		way(ribs, 4, 7, 'li0', 2);
		way(ribs, 4, 9, 'li0', 4);

		rib(5, ribs);
		way(ribs, 5, 4, 'li0', 2);
		way(ribs, 5, 6, 'li0', 1);
		way(ribs, 5, 10, 'li0', 2);
		way(ribs, 5, 11, 'li0', 4);

		rib(6, ribs);
		way(ribs, 6, 5, 'li0', 1);
		way(ribs, 6, 7, 'li0', 3);

		rib(7, ribs);
		way(ribs, 7, 2, 'li0', 3);
		way(ribs, 7, 4, 'li0', 2);
		way(ribs, 7, 6, 'li0', 3);
		way(ribs, 7, 8, 'li0', 3);

		rib(8, ribs);
		way(ribs, 8, 1, 'li0', 1);
		way(ribs, 8, 7, 'li0', 3);

		rib(9, ribs);
		way(ribs, 9, 4, 'li0', 4);
		way(ribs, 9, 10, 'li0', 1);

		rib(10, ribs);
		way(ribs, 10, 9, 'li0', 1);
		way(ribs, 10, 5, 'li0', 2);
		way(ribs, 10, 12, 'li0', 2);

		rib(11, ribs);
		way(ribs, 11, 5, 'li0', 4);
		way(ribs, 11, 12, 'li0', 1);

		rib(12, ribs);
		way(ribs, 12, 10, 'li0', 2);
		way(ribs, 12, 11, 'li0', 1);

		return ribs;
	}

	graph = init();

	var d = new adijkstra();

	var result = d.init(graph, 6, 1, function () {

		console.log("result");
	});

	//d.destroy();

	//listenServer();
}

function loadGraph() {

	console.log('loadGraph');

	queryRows('select *, st_asgeojson(geog) as geog from routegraph', [], function (nodes) {

		console.log(nodes.length);

		for (var i in nodes) if (nodes.hasOwnProperty(i)) {

			var node = nodes[i];

			node['ways'] = JSON.parse(node['ways']);

			node['geog'] = JSON.parse(node['geog']);

			graph[node['id']] = node;
		}

		listenServer();
	});

}

loadGraph();