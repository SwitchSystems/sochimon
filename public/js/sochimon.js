// create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([51.505, -0.09], 13);
var plottedPolyline;
var currentMarkers = new Object();

// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

$('').click(function(){
	id = $(this).data('sochi-id')
	getMarkerData(id);
});

function getMarkerData(id)
{
	$.get('/api/XXX/' + id, createMarker, 'json').done(function(){});
}

function createMarker(obj, ajaxStatus)
{
	L.marker(obj.data.latLng).addTo(map)
	
	currentMarkers[obj.data.id] = obj.data.latLng;
	
	addMarkerToRoute();
}

function addMarkerToRoute() {
	plottedPolyline = L.Polyline.Plotter(currentMarkers,{weight: 5}).addTo(map);
}


