// create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([51.505, -0.09], 4);
var plottedPolyline;
var currentMarkers = new Object();

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

	countryDiv = $('.countries').find("[data-country='" + country + "']");
	var lat = $(countryDiv).data('country-lat');
	var lng = $(countryDiv).data('country-Lng');

	//var img = countryDiv.find('img');

	createMarker(lat, lng, country);
}

function createMarker(lat, lng, country)
{
	//customIcon = createCustomIcon(imgSrc);
	popupHtml  = createPopupMarkup(country);
	marker = L.marker([lat, lng]).addTo(map);
	currentMarkers[country] = marker;
console.log(marker);
	addMarkerToRoute();
}

function createPopupMarkup(country) {
	popupHtml=document.createElement('DIV');

	$(popupHtml).append(country);

	return popupHtml;
}

function addMarkerToRoute() {
	plottedPolyline = L.Polyline.Plotter(currentMarkers,{weight: 5}).addTo(map);
}

function createCustomIcon(imgSrc) {
	var customIcon = L.icon({
			iconUrl: imgSrc,

			iconSize:     [38, 95], // size of the icon
			shadowSize:   [50, 64], // size of the shadow
			iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			shadowAnchor: [4, 62],  // the same for the shadow
			popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
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