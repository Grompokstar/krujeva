$('header').remove();
$('body').attr("style", "padding:0");

/* mode edit points
var coordinates = [];
var onMapClick = function (e) {
	console.log(e.latlng.lat, e.latlng.lng);

	var circle = L.circle([e.latlng.lat, e.latlng.lng], 500, {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.5
	}).addTo(App.home.map);

	coordinates.push([e.latlng.lat, e.latlng.lng]);
};

App.home.map.on('click', onMapClick);
*/


var kazanCity = [
	[55.772904096077305, 49.14304733276367],
	[55.77242131739289, 49.16141510009765],
	[55.78091734876589, 49.15300369262695],
	[55.76382685625667, 49.1502571105957],
	[55.76305422761223, 49.1696548461914],
	[55.77145574208505, 49.18046951293945],
	[55.780820813358616, 49.172401428222656],
	[55.7832341267933, 49.13309097290039],
	[55.77396618813479, 49.12416458129883],
	[55.79028014659872, 49.14682388305664],
	[55.792596367910356, 49.12879943847656],
	[55.78690207695225, 49.11661148071289],
	[55.77946929254521, 49.105281829833984],
	[55.7918243094437, 49.10305023193359],
	[55.800895029938275, 49.12055969238281],
	[55.79944771620931, 49.1392707824707],
	[55.79114874573143, 49.16879653930664],
	[55.79983367179713, 49.17737960815429],
	[55.80658727534378, 49.185447692871094],
	[55.782848006688226, 49.19334411621094],
	[55.75822495171307, 49.18853759765625],
	[55.75310526627228, 49.20896530151367],
	[55.74817814201809, 49.189910888671875],
	[55.73996488528175, 49.20724868774414],
	[55.74218746653594, 49.22098159790039],
	[55.75088330688492, 49.23505783081055],
	[55.761798673411796, 49.23986434936523],
	[55.770779825137836, 49.23728942871094],
	[55.782654945200754, 49.23093795776367],
	[55.793947433402685, 49.222869873046875],
	[55.802149324988534, 49.208450317382805],
	[55.78429593735242, 49.21205520629882],
	[55.77280754081879, 49.21205520629882],
	[55.76344054384817, 49.21274185180664],
	[55.75011042177816, 49.17051315307617],
	[55.741414409152696, 49.16656494140625],
	[55.73696903199785, 49.18355941772461],
	[55.729913372305155, 49.170169830322266],
	[55.735326047078075, 49.15386199951171],
	[55.745086295331475, 49.13841247558593],
	[55.75581008948492, 49.126224517822266],
	[55.767979472925646, 49.092750549316406],
	[55.76517891954544, 49.117984771728516],
	[55.795877445664104, 49.0894889831543],
	[55.800219623559414, 49.0675163269043],
	[55.80842019416513, 49.07798767089844],
	[55.81227868853418, 49.09395217895508],
	[55.815654557347024, 49.11214828491211],
	[55.817197714130856, 49.1282844543457],
	[55.815172308302344, 49.142017364501946],
	[55.8227911445053, 49.15763854980468],
	[55.83224028603308, 49.15678024291992],
	[55.842843731283345, 49.15369033813476],
	[55.83802434189814, 49.137210845947266],
	[55.82838377081664, 49.13909912109375],
	[55.825876830864026, 49.12244796752929],
	[55.834650413863756, 49.12055969238281],
	[55.82529828331482, 49.108028411865234],
	[55.834843217637676, 49.09807205200195],
	[55.82230898390269, 49.091033935546875],
	[55.831661833127946, 49.08004760742187],
	[55.818451483958896, 49.07764434814453],
	[55.82635894724889, 49.06511306762695],
	[55.814690053281524, 49.06288146972656],
	[55.82230898390269, 49.0488052368164],
	[55.80822725940602, 49.050350189208984],
	[55.832336694014145, 49.048118591308594],
	[55.83869909237606, 49.06442642211913],
	[55.84255458476687, 49.0839958190918],
	[55.84419305325834, 49.107513427734375],
	[55.84477131976775, 49.123992919921875],
	[55.85055351175813, 49.14081573486328],
	[55.86375295936749, 49.09446716308594],
	[55.86490890178817, 49.07712936401367],
	[55.86577583602948, 49.06116485595703],
	[55.866739073604236, 49.04125213623047],
	[55.84949350761968, 49.06373977661133],
	[55.85132440570026, 49.04571533203125],
	[55.85431147529719, 49.02975082397461],
	[55.74469979726796, 49.114036560058594],
	[55.73464950377399, 49.12639617919921],
	[55.797710868722575, 49.15815353393555],
	[55.768607155567956, 49.19746398925781],
	[55.84139797719491, 49.03953552246094],
	[55.824141162403144, 49.02923583984374],
	[55.797517880886495, 49.013442993164055],
	[55.79616693925402, 49.03266906738281],
	[55.79433344350657, 49.05258178710937],
	[55.77763501074921, 49.060821533203125],
	[55.77946929254521, 49.07764434814453],
	[55.75687264728658, 49.07661437988281],
	[55.74614914526591, 49.097042083740234],
	[55.752332425188385, 49.150428771972656],
	[55.74556941252715, 49.15712356567383],
	[55.764792620532894, 49.13309097290039],
	[55.78060360781787, 49.12070989608765]
];

var timeout = 1000 / (kazanCity.length / 60);

//var timeout = 5000;

if (timeout < 350) {
	timeout = 350;
}

var initCity = function (list, index) {

	console.log('init city', index);

	if (!index) {
		index = 0;
	}

	if (typeof list[index] == 'undefined') {
		return initCity(list, 0);
	}

	var coors = list[index];

	App.home.findNearbyPokemon(coors[0], coors[1]);

	setTimeout(function () {
		initCity(list, (index + 1));
	}, timeout);
};

initCity(kazanCity, 0);

//App.home.findNearbyPokemon(55.779998, 49.170116);
//App.home.findNearbyPokemon(55.849404, 48.509631);


$('header').remove();
$('body').attr("style", "padding:0");
$('.dropdown-menu li a').trigger('click');

setTimeout(function () {

	var pockemons = ["0", "1", "2", "3", "4", "5", "8", "9", "10", "12", "13", "14", "15", "19", "20", "23", "25", "26", "27", "32", "33", "42", "46", "47", "48", "49", "50", "51", "52", "55", "56", "58", "62", "64", "68", "70", "71", "72", "73", "76", "83", "85", "86", "87", "92", "96", "98", "107", "112", "113", "116", "120", "125", "127", "129", "133", "139", "144", "148", "149"];

	for (var i in pockemons) {
		$('.dropdown-menu li[data-original-index=' + pockemons[i] + ']').addClass('selected').find('a').trigger('click');
	}
}, 2000);
