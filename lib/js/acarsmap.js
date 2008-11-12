var map;
var markers = [];

$(document).ready(function() { if (GBrowserIsCompatible()) {

	map = new GMap2(document.getElementById("acarsmap"));

    map.setCenter(new GLatLng(45.484400, -62.334821), 16, G_PHYSICAL_MAP);
    var bds = new GLatLngBounds(new GLatLng(37.4639564, -123.58687772), new GLatLng(53.5048436, -1.08276428));
    map.setZoom(map.getBoundsZoomLevel(bds));

    map.addControl(new GLargeMapControl());
    map.addControl(new GMapTypeControl());
    map.addControl(new GScaleControl());

	liveRefresh();
	
	setInterval(function () { liveRefresh(); }, 15000);
}});

function liveRefresh()
{
	 GDownloadUrl(urlbase+"/action.php/acars/acarsdata", 
	 function(data) 
	 {
        var xml = GXml.parse(data);
        var aircraft = xml.documentElement.getElementsByTagName("aircraft");
        
        clearMap();
        
        for(var i=0; i<aircraft.length; i++) 
        {
			var lat = parseFloat(aircraft[i].getAttribute("lat"));
			var lng = parseFloat(aircraft[i].getAttribute("lng"))
			var flightnum = aircraft[i].getAttribute("flightNum");
			
     		var point = new GLatLng(lat, lng);
     		
			//icon = markers[i].childNodes[0].textContent;
			icon = GXml.value(aircraft[i].getElementsByTagName("icon")[0]);
			details = GXml.value(aircraft[i].getElementsByTagName("details")[0]);
			pilotid = GXml.value(aircraft[i].getElementsByTagName("pilotid")[0]);
			pilotname = GXml.value(aircraft[i].getElementsByTagName("pilotname")[0]);
			depicao = GXml.value(aircraft[i].getElementsByTagName("depicao")[0]);
			arricao = GXml.value(aircraft[i].getElementsByTagName("arricao")[0]);
			
			var statusIcon= new GIcon(G_DEFAULT_ICON);
			statusIcon.image = icon;
			statusIcon.iconSize = new GSize(35,35);
			markerOptions = { icon:statusIcon };
			
			sidebar='<a href="javascript:triggerbubble(' + markers.length + ')">' + pilotid+' '+pilotname + '</a><br />';
			
			document.getElementById("pilotlist").innerHTML = document.getElementById("pilotlist").innerHTML + sidebar;

			markers[markers.length]= createMarker(point, markerOptions, flightnum, details);
			map.addOverlay(markers[markers.length-1]);
        }
    });
};

function triggerbubble(marker_id)
{
	GEvent.trigger(markers[marker_id], "click");
}

function createMarker(point, markerOptions, flightnum, details)
{
	var marker = new GMarker(point, markerOptions);
	GEvent.addListener(marker, "click", 
		function() { 
			marker.openInfoWindowHtml(details); 
			clickFlight(marker, flightnum); 
		});
			
	return marker;
};

// Add an overlay with the selected flight's projected route
function clickFlight(marker, flightnum)
{
	return; 
	
	GDownloadUrl(urlbase+"/action.php/acars/flightdata?route="+flightnum, 
	 function(data) 
	 {
		var polyOptions = {geodesic:true};
		var polyline = new GPolyline([
		  new GLatLng(40.65642, -73.7883),
		  new GLatLng(61.1699849, -149.944496)
		  ], "#ff0000", 10, 1, polyOptions);
		  
		  map.addOverlay(polyline);
	 });
};

function clearMap()
{
	if(markers.length > 0)
	{
		for(var i = 0; i < markers.length; i++)
		{
			GEvent.removeListener(markers[i]);
			map.removeOverlay(markers[i]);
		}
	}
	
	markers.length = 0;
}
