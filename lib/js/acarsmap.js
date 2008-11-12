var map;
var markers = [];

$(document).ready(function() { if (GBrowserIsCompatible()) 
{
	map = new GMap2(document.getElementById("acarsmap"));

    map.setCenter(new GLatLng(45.484400, -62.334821), 13, G_PHYSICAL_MAP);
    var bds = new GLatLngBounds(new GLatLng(37.4639564, -123.58687772), 
								new GLatLng(53.5048436, -1.08276428));
    map.setZoom(map.getBoundsZoomLevel(bds));

    map.addControl(new GLargeMapControl());
    map.addControl(new GMapTypeControl());
    map.addControl(new GScaleControl());

	liveRefresh();
	setInterval(function () { liveRefresh(); }, 15000);	
}});

function liveRefresh()
{
	clearMap();
	
	GDownloadUrl(urlbase+"/action.php/xml/acarsdata", 
	 function(data) 
	 {
        var xml = GXml.parse(data);
        var aircraft = xml.documentElement.getElementsByTagName("aircraft");
        markers.length=0;
        
        var row = "<tr><td><b>Pilot</b></td><td><b>Flight Number</b></td><td><b>Departure</b></td><td><b>Arrival</b></td><td><b>Status</b></td><td><b>Altitude</b></td><td><b>Speed</b></td><td><b>Distance/Time Remain</b></td></tr>";
        $("#pilotlist").html(row);
        
        for(var i=0; i<aircraft.length; i++) 
        {
			var lat = parseFloat(aircraft[i].getAttribute("lat"));
			var lng = parseFloat(aircraft[i].getAttribute("lng"))
			var flightnum = aircraft[i].getAttribute("flightnum");
			
     		var point = new GLatLng(lat, lng);
     		
			//icon = markers[i].childNodes[0].textContent;
			var icon = GXml.value(aircraft[i].getElementsByTagName("icon")[0]);
			var details = GXml.value(aircraft[i].getElementsByTagName("details")[0]);
			var pilotid = GXml.value(aircraft[i].getElementsByTagName("pilotid")[0]);
			var pilotname = GXml.value(aircraft[i].getElementsByTagName("pilotname")[0]);
			var phase = GXml.value(aircraft[i].getElementsByTagName("phase")[0]);
			var alt = GXml.value(aircraft[i].getElementsByTagName("alt")[0]);
			var gs = GXml.value(aircraft[i].getElementsByTagName("gs")[0]);
			var distremain = GXml.value(aircraft[i].getElementsByTagName("distremain")[0]);
			var timeremain = GXml.value(aircraft[i].getElementsByTagName("timeremain")[0]);
			var depicao = GXml.value(aircraft[i].getElementsByTagName("depicao")[0]);
			var arricao = GXml.value(aircraft[i].getElementsByTagName("arricao")[0]);
			
			var statusIcon= new GIcon(G_DEFAULT_ICON);
			statusIcon.image = icon;
			statusIcon.iconSize = new GSize(35,35);
			markerOptions = { icon:statusIcon };
			
			sidebar='<a href="javascript:triggerInfoBubble(' + markers.length + ')">' + pilotid+' '+pilotname + '</a>';
					
			var row = "<tr><td>"+sidebar+"</td><td>"+flightnum+"</td><td>"+depicao+"</td><td>"+arricao+"</td><td>"+phase+"</td><td>"+alt+"</td><td>"+gs+"</td><td>"+distremain+"mi/"+timeremain+"min </td></tr>";
			$("#pilotlist").append(row);	

			markers[markers.length]= createMarker(point, markerOptions, flightnum, details);
			map.addOverlay(markers[markers.length-1]);
        }
    });
};

function triggerInfoBubble(marker_id)
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
	GEvent.clearListeners(map, 'click');
	
	if(markers.length > 0) {
		for(var i = 0; i < markers.length; i++) {
			map.removeOverlay(markers[i]);
		}
	}
	markers.length = 0;
}