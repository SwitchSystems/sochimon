// create a map in the "map" div, set the view to a given place and zoom

var map = L.map('map').setView([51.505, -0.09], 2);

var currentMarkers = new Object();
var plottedPolyline = new Object();

//disable drag and zoom handlers
map.dragging.disable();
map.touchZoom.disable();
map.doubleClickZoom.disable();
map.scrollWheelZoom.disable();
// disable tap handler, if present.
if (map.tap) map.tap.disable();

currentMarkers.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};



// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors', continuousWorld: false
}).addTo(map);

function getMarkerData(e)
{	
	e.preventDefault();
	var country = $(e.target).data('country');
	var detail = $('li.country-detail[data-country="'+country+'"]');
	$('#route').append(detail);
	$('#route li[data-country="'+country+'"]').show();
	$('#countries li[data-country="'+country+'"]').remove();
	
	if($('#countries li').length == 0) {
		$('#calculateRoute').show();
	}
	
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
	//marker = L.marker(currentLatLng).addTo(map);

	currentMarkers[country] = currentLatLng;
	var pos = $('.leaflet-marker-pane').find('img:last').position();
	$('.leaflet-marker-pane').find('img').css({height: '15px', width: '20px'});
	if(typeof previousLatLng[0] != 'undefined') {
		addMarkerToRoute(currentLatLng, previousLatLng);
		$('.plane').animate({left: pos.left, top: pos.top}, 'slow');
	}
	else {
		$('.leaflet-map-pane').append("<div class='plane icon-plane' style='font-size: 2em;z-index:9999;position:absolute;top:" + pos.top + 'px;left:' + pos.left + "px;'></div>");
	}
	 panZoomToLayer(currentLatLng);
	 
	 
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

			iconSize:     [20, 15], // size of the icon
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

function panZoomToLayer(currentLatLng){	
	//map.panTo(currentLatLng, {animation: 'slow'});
}