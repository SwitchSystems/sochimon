// create a map in the "map" div, set the view to a given place and zoom

var map = L.map('map').setView([51.505, -0.09], 2);
var currentMarkers = new Object();
var plottedPolyline = new Object();


currentMarkers.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

function getMarkerData(e)
{
	e.preventDefault();
	var country = $(e.target).data('country');
	var detail = $('li.country-detail[data-country="'+country+'"]');
	$('#route').append(detail);
	$('#route li[data-country="'+country+'"]').show();
	$('#countries li[data-country="'+country+'"]').remove();

	countryDiv = $('#route').find("[data-country='" + country + "']");
	var lat = countryDiv.data('lat');
	var lng = countryDiv.data('lng');
	
	var prevLat = countryDiv.prev().data('lat');
	var prevLng = countryDiv.prev().data('lng');
	
	var img = countryDiv.find('img').attr('src');

	createMarker([lat, lng], [prevLat, prevLng], img, country);
}

function createMarker(currentLatLng, previousLatLng, img, country)
{	 
	customIcon = createCustomIcon(img);
	popupHtml  = createPopupMarkup(country);
	marker = L.marker(currentLatLng, {icon: customIcon}).addTo(map);
	currentMarkers[country] = currentLatLng;

	addMarkerToRoute(currentLatLng, previousLatLng);
}

function createPopupMarkup(country) {
	popupHtml=document.createElement('DIV');

	$(popupHtml).append(country);

	return popupHtml;
}

function addMarkerToRoute(current, previous) {	
	L.polyline([current, previous],{color: 'red'}).addTo(map);
}

function createCustomIcon(imgSrc) {
	var customIcon = L.icon({
			iconUrl: imgSrc,

			iconSize:     [45, 34], // size of the icon
			//iconAnchor:   [0, 34], // point of the icon which will correspond to marker's location
			//shadowAnchor: [4, 62],  // the same for the shadow
			//popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
	});


	return customIcon;
}

function deleteMarkers(id) {
	map.removeLayer(currentMarkers[id]);
};

function getMarkerDataCallback() {

}

//country select
$(document).ready(function(){
	$('li.country').click(function(e){
		getMarkerData(e)
	});
});

$('#calculateRoute').click(function(){
	
	var data = getRoute();
	
	$.ajax({
		  type: "POST",
		  url: '/game/score',
		  data: data,
		  success: displayScore,
	});
});	



function getRoute() {
	var countriesList = '';
	var routes = $('#route').find('.country-detail');
	
	routes.each(function(){
		countriesList += 'countries[]='+$(this).data('country') + '&';
	});
	
	return countriesList;
}

function displayScore(obj, status) {
	
	alert(obj.score);
}