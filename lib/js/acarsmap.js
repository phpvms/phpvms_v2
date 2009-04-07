/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
var map;
var markers = [];
var aptmarkers = [];
var polyline;
var activeMarker;

$(document).ready(function() { if (GBrowserIsCompatible()) 
{
	map = new GMap2(document.getElementById("acarsmap"));

    map.setCenter(new GLatLng(map_center_lat , map_center_lng), 13, map_type);
    var bds = new GLatLngBounds(new GLatLng(37.4639564, -123.58687772), 
								new GLatLng(53.5048436, -1.08276428));
								
    map.setZoom(map.getBoundsZoomLevel(bds));

    map.addControl(new GLargeMapControl());
    map.addControl(new GMapTypeControl());
    map.addControl(new GScaleControl());

	liveRefresh();
	setInterval(function () { liveRefresh(); }, 60000);
	
}});

function liveRefresh()
{
	clearMap();
	var rand=Math.floor(Math.random()*1000)
	var url = window.location.href.split("index.php")[0];
	
	GDownloadUrl(url+"/action.php/xml/acarsdata?rand="+rand, 
	 function(data) 
	 {
        var xml = GXml.parse(data);
        var aircraft = xml.documentElement.getElementsByTagName("aircraft");
        markers.length=0;
        $("#pilotlist").html('');
         
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
			
			var pilotlink='<a href="#" onClick="return triggerInfoBubble(' + markers.length + ');">' + pilotid+' '+pilotname + '</a>';
					
			var row = "<tr><td>"+pilotlink+"</td><td>"+flightnum+"</td><td>"+depicao+"</td><td>"+arricao+"</td><td>"+phase+"</td><td>"+alt+"</td><td>"+gs+"</td><td>"+distremain+"mi/"+timeremain+"min </td></tr>";
			$("#pilotlist").append(row);	

			markers[markers.length]= createMarker(point, markerOptions, depicao, arricao, details);
			map.addOverlay(markers[markers.length-1]);
        }
    });
};

function triggerInfoBubble(marker_id)
{
	GEvent.trigger(markers[marker_id], 'click');
	return false;
}

function createMarker(point, markerOptions, depicao, arricao, details)
{
	var marker = new GMarker(point, markerOptions);
	
	GEvent.addListener(marker, "click", 
		function() { 
		
			if(activeMarker == marker)
				return;
			else
				activeMarker = marker;
				
			marker.openInfoWindowHtml(details); 				
			if(depicao != '' && arricao != '')
			{
				clickFlight(marker, depicao, arricao); 
			}
		});
			
	return marker;
};

function clickFlight(marker, depicao, arricao)
{			
	clearFlightDetails();
	var url = window.location.href.split("index.php")[0];
	
	GDownloadUrl(url+"/action.php/xml/routeinfo?depicao="+depicao+"&arricao="+arricao, 
	 function(data) 
	 {
		if(data == '') return;
		var xml = GXml.parse(data);
		
        var departure = xml.documentElement.getElementsByTagName("departure");
        var arrival = xml.documentElement.getElementsByTagName("arrival");
        
        if(departure == null || arrival == null)
			return;
			
        var depicao = departure[0].getAttribute("icao");
        var depname = departure[0].getAttribute("name");
        var depctry = departure[0].getAttribute("country");
        var deplat = departure[0].getAttribute("lat");
        var deplng = departure[0].getAttribute("lng");
        
        var arricao = arrival[0].getAttribute("icao");
        var arrname = arrival[0].getAttribute("name");
        var arrctry = arrival[0].getAttribute("country");
        var arrlat = arrival[0].getAttribute("lat");
        var arrlng = arrival[0].getAttribute("lng");
        
        // Add poly line
		var polyOptions = {geodesic:true};
		polyline = new GPolyline([
			  new GLatLng(deplat,deplng),
			  new GLatLng(arrlat, arrlng)
		  ], "#ff0000", 3, 1, polyOptions);
		  
		map.addOverlay(polyline);
		
		// Add the airport markers
		
		// Departure
		var depIcon = new GIcon(G_DEFAULT_ICON);
		depIcon.image = url + '/lib/images/towerdeparture.png';
		depIcon.iconSize = new GSize(35,35);
		markerOptions = { icon:depIcon };
     	var point = new GLatLng(deplat, deplng);
     	var depdet = '<span style="font-size: 10px">'+depname+'('+depicao+')<br /><b>Country:</b>'+depctry+'</span>';
		aptmarkers[0]= createMarker(point, markerOptions, '', '', depdet);
		map.addOverlay(aptmarkers[0]);
		
		// Arrival
		var arrIcon= new GIcon(G_DEFAULT_ICON);
		arrIcon.image = url + '/lib/images/towerarrival.png';;
		arrIcon.iconSize = new GSize(35,35);
		markerOptions = { icon:arrIcon };
     	var point = new GLatLng(arrlat, arrlng);
		var arrdet = '<span style="font-size: 10px">'+arrname+'('+arricao+')<br /><b>Country:</b>'+arrctry+'</span>';
		aptmarkers[1]= createMarker(point, markerOptions, '', '', arrdet);
		map.addOverlay(aptmarkers[1]);
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

function clearFlightDetails()
{
	if(aptmarkers.length > 0) 
	{
		map.removeOverlay(polyline);
		
		for(var i = 0; i < aptmarkers.length; i++) {
			map.removeOverlay(aptmarkers[i]);
		}
	}
	aptmarkers.length = 0;
}