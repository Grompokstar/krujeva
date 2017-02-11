var listitems = [];
var whitelist = ['Анбэцу', 'Джамбейты', 'Джаныбек', 'Каховское'];

//@ init  initItteration()

var goNext = function (index) {

	var item = null;

	for (var i in listitems) if (listitems.hasOwnProperty(i)) {

		if (i < index) {
			continue;
		}

		item = listitems[i];
		break;
	}

	if (!item) {
		listitems = [];
		initItteration();
		return;
	}

	getYandexPolygon(item, item['id'], index, 1);
};

var initItteration = function () {

	$.ajax({url: "https://shuga.agency/market/cities/listitems?offset=0&limit=20", success: function (result) {

		if (result && result.data) {

			listitems = result.data;

			goNext(0);

		} else {

			alert('end list !!!');
		}
	}});
}

var formText = function (item, type) {

	if (item['areaname'] == 'Санкт-Петербург и область') {
		item['areaname'] = 'Ленинградская область';
	}

	if (type == 1) {

		return item['areaname'] + ', город ' + item['cityname'];

	}

	if (type == 2) {

		return item['areaname'] + ', поселок ' + item['cityname'];

	}

	if (type == 3) {

		return item['areaname'] + ', село ' + item['cityname'];

	}

	if (type == 4) {

		return item['areaname'] + ', столица ' + item['cityname'];

	}

	return 'область ' + item['areaname'] + ', село ' + item['cityname'];

}

var getYandexPolygon = function (item, id, index, type) {

	//@white list
	if (whitelist.indexOf(item['cityname']) != -1) {
		goNext((index + 1));
		return;
	}

	var text = formText(item, type);

	console.log(text);

	var url = 'https://maps.yandex.ru/api/search?text=' + text +'&lang=ru_RU&yandex_gid=43&origin=maps-form&results=20&z=8&ll=49.10271299999994%2C55.770258774377474&spn=5.64697265625%2C0.579060346465333&snippets=business%2F1.x%2Cmasstransit%2F1.x%2Cpanoramas%2F1.x%2Cbusinessrating%2F2.x%2Cphotos%2F1.x&ask_direct=1&direct_page_id=242&direct_stat_id=9&experimental_maxadv=20&csrfToken=5ba19104d26fe7b6528c057ebffbf8542b84f60c%3A1458638555144&sessionId=1458638555099_691958';

	//var url = 'https://maps.yandex.ru/api/search?text=%D0%9A%D1%80%D1%8B%D0%BC%D1%81%D0%BA%D0%B0%D1%8F%20%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C,%20%D0%BF%D0%BE%D1%81%D0%B5%D0%BB%D0%BE%D0%BA%20%D0%90%D0%B7%D0%BE%D0%B2%D1%81%D0%BA%D0%BE%D0%B5&lang=ru_RU&yandex_gid=43&origin=maps-form&results=20&z=8&ll=49.10271299999994%2C55.770258774377474&spn=5.64697265625%2C0.579060346465333&snippets=business%2F1.x%2Cmasstransit%2F1.x%2Cpanoramas%2F1.x%2Cbusinessrating%2F2.x%2Cphotos%2F1.x&ask_direct=1&direct_page_id=242&direct_stat_id=9&experimental_maxadv=20&csrfToken=5ba19104d26fe7b6528c057ebffbf8542b84f60c%3A1458638555144&sessionId=1458638555099_691958';


	function needNextRound(type, item, id, index) {

		if (type == 1) {

			getYandexPolygon(item, id, index, 2);

		} else if (type == 2) {

			getYandexPolygon(item, id, index, 3);

		} else if (type == 3) {

			getYandexPolygon(item, id, index, 4);

		} else if (type == 4) {

			getYandexPolygon(item, id, index, 5);

		} else {

			//@todo
			console.log('Error' + item);

			//goNext((index + 1));

			//callback(null, item);

		}
	}

	$.ajax({url: url, success: function (result) {

		//if (result && result.data && result.data['features'] && result.data['features'][0] && result.data['features'][0]['properties'] && result.data['features'][0]['properties']['GeocoderMetaData'] && result.data['features'][0]['properties']['GeocoderMetaData']['InternalToponymInfo'] && result.data['features'][0]['properties']['GeocoderMetaData']['InternalToponymInfo']['MultiGeometry'] && result.data['features'][0]['properties']['GeocoderMetaData']['InternalToponymInfo']['MultiGeometry']['geometries']) {

		if (result && result.data && result.data['features'] && result.data['features'][0] && result.data['features'][0]['geometries']) {

			var geogs = result.data['features'][0]['geometries'];

			var max = null;

			var maxCount = 0;

			for (var i in geogs) if (geogs.hasOwnProperty(i)) {

				var pg = geogs[i];

				if (pg['geometries'] && pg['geometries'][0]) {
					pg = pg['geometries'][0];
				}

				if (!pg['coordinates']) {
					continue;
				}

				if (pg['coordinates'][0].length > maxCount) {
					maxCount = pg['coordinates'][0].length;
					max = pg;
				}
			}

			//@close ring
			if (max) {
				var first = max['coordinates'][0][0];

				var last = max['coordinates'][0][(max['coordinates'][0].length - 1)];

				if (first[0] !== last[0] || first[1] !== last[1]) {
					max['coordinates'][0].push(first);
				}

				max['coordinates'] = [max['coordinates'][0].slice()];
			}


			if (!max) {

				needNextRound(type, item, id, index);

				return;
			}

			$.ajax({
				url: "https://shuga.agency/market/cities/savegeom",
				method: 'post',
				data: {id: id, geog: JSON.stringify(max) },
				success: function (result) {
					goNext((index + 1));
				}
			});

		} else {

			needNextRound(type, item, id, index);

		}

	}});
};