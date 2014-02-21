// create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([51.505, -0.09], 13);
var plottedPolyline;
var currentMarkers = new Object();

// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

$('').click(function(){
	id = $(this).data('')
	getMarkerData(id);
});

function getMarkerData(e)
{
	e.preventDefault();
	var country = $(e.target).data('country');
	var detail = $('li.country-detail[data-country="'+country+'"]');
	var id  = $(e.target).data('country-latLng');
	$('#route').append(detail);
	$('#route li[data-country="'+country+'"]').show();
	$('#countries li[data-country="'+country+'"]').remove();

	createMarkerFrom(id, imgSrc);
}

function createMarker(latLng, imgSrc)
{
	customIcon = createCustomIcon(imgSrc);
	popupHtml  = createPopupMarkup(obj);
	marker = L.marker(latLng, {icon: customIcon}).addTo(map).bindPopup(popupHtml);
	currentMarkers[obj.data.id] = marker;

	addMarkerToRoute();
}

function createPopupMarkup(obj) {
	popupHtml=document.createElement('DIV');

	$(popupHtml).append(obj.data.name);

	return popupHtml;
}

function addMarkerToRoute() {
	plottedPolyline = L.Polyline.Plotter(currentMarkers,{weight: 5}).addTo(map);
}

function createCustomIcon(obj) {
	var customIcon = L.icon({
			iconUrl: obj.data.flag,

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