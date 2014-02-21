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
	$.get('/api/XXX/' + id, createMarkerFromJson, 'json').done(function(){getMarkerDataCallback();});
}

function createMarkerFromJson(obj, ajaxStatus)
{
	customIcon = createCustomIcon(obj);
	L.marker(obj.data.latLng, {icon: customIcon}).addTo(map).bindPopup("I am a green leaf.");;
	currentMarkers[obj.data.id] = obj.data.latLng;
	
	addMarkerToRoute();
}

function createPopupMarkup(obj) {
	popupHtml=document.createElement('DIV');
	
	$(popupHtml).append(obj.data.name);
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

function getMarkerDataCallback() {
	
}