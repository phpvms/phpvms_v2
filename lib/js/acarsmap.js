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
var flight_clicked;

// change in local.config.php
var map_zoom_level;
var map_center_lat;
var map_center_lng;
var map_type;

$(document).ready(function() { if (GBrowserIsCompatible()) 
{
	map = new GMap2(document.getElementById("acarsmap"));

	if(map_zoom_level == '')
	{
		map_zoom_level = 12;
	}
	
	map.setCenter(new GLatLng(map_center_lat , map_center_lng), map_zoom_level, map_type);
	var bds = new GLatLngBounds(new GLatLng(37.4639564, -123.58687772), 
								new GLatLng(53.5048436, -1.08276428));

	map.setZoom(map.getBoundsZoomLevel(bds));
	map.addControl(new GLargeMapControl());
	map.addControl(new GMapTypeControl());
	map.addControl(new GScaleControl());

	liveRefresh();
	setInterval(function () { liveRefresh(); }, 6000);
}});

function liveRefresh()
{
	clearMap();

	$.ajax({
	    type: "GET",
	    url: url + "/action.php/acars/data",
	    dataType: "json",
	    success: function(data) {
	        $("#pilotlist").html("");

	        if (data.length == 0) {
	            return false;
	        }

	        for (var i = 0; i < data.length; i++) {
	            var lat = data[i].lat;
	            var lng = data[i].lng;

	            var details = '<span style="font-size: 10px; text-align:left; width: 100%" align="left">'
					+ '<a href="' + url + '/index.php/profile/view/' + data[i].pilotid + '">' + data[i].pilotid + ' - ' + data[i].pilotname + '</a><br />'
					+ '<strong>Flight ' + data[i].flightnum + '</strong> (' + data[i].depicao + ' to ' + data[i].arricao + ')<br />'
					+ '<strong>Status: </strong>' + data[i].phasedetail + '<br />'
					+ '<strong>Dist/Time Remain: </strong>' + data[i].distremaining + ' / ' + data[i].timeremaining + ' h:m<br />'
					+ '</span>';

	            var statusIcon = new GIcon(G_DEFAULT_ICON);
	            statusIcon.image = data[i].icon;
	            statusIcon.iconSize = new GSize(36, 36);
	            markerOptions = { icon: statusIcon };

	            var pilotlink = '<a href="#" onClick="return triggerInfoBubble(' + markers.length + ');">' + data[i].pilotid + ' ' + data[i].pilotname + '</a>';
	            var row = "<tr><td>" + pilotlink + "</td><td>" + data[i].flightnum + "</td><td>" + data[i].depicao + "</td>"
					+ "<td>" + data[i].arricao + "</td><td>" + data[i].phasedetail + "</td><td>" + data[i].alt + "</td>"
					+ "<td>" + data[i].gs + "</td><td>" + data[i].distremaining + "mi/" + data[i].timeremaining + " </td></tr>";

	            $("#pilotlist").append(row);

	            markers[markers.length] = createMarker(data[i], markerOptions, details);
	            map.addOverlay(markers[markers.length - 1]);
	        }


	        if (flight_clicked == true) {
	            clickFlight(activeMarker);
	        }
	    }
	});
};

function triggerInfoBubble(marker_id)
{
	GEvent.trigger(markers[marker_id], 'click');
	return false;
}

function createMarker(data, markerOptions, details)
{
	var point = new GLatLng(data.lat, data.lng);
	var marker = new GMarker(point, markerOptions);
	marker.acarsdata = data;

	GEvent.addListener(marker, "click",  function() 
	{ 
		if(activeMarker == marker)
			return;
		else
			activeMarker = marker;

		marker.openInfoWindowHtml(details); 				
		if(data.depicao != '' && data.arricao != '')
		{
			clickFlight(marker); 
		}
	});
	return marker;
};

function clickFlight(marker)
{			
	clearFlightDetails();
	flight_clicked = true;
	
	var polyOptions = {geodesic:true};
	polyline = new GPolyline([
			new GLatLng(marker.acarsdata.deplat, marker.acarsdata.deplng),
			new GLatLng(marker.acarsdata.lat, marker.acarsdata.lng),
			new GLatLng(marker.acarsdata.arrlat, marker.acarsdata.arrlng),
		], "#ff0000", 3, 1, polyOptions);

	map.addOverlay(polyline);
	
	markerOptions = { icon:depIcon };
	var depdet = '<span style="font-size: 10px; text-align:left">'+marker.acarsdata.depname+'('+marker.acarsdata.depicao+')</span>';
	aptmarkers[0]= createMarker({lat: marker.acarsdata.deplat, lng: marker.acarsdata.deplng}, markerOptions, depdet);
	map.addOverlay(aptmarkers[0]);

	markerOptions = { icon:arrIcon };
	var arrdet = '<span style="font-size: 10px">'+marker.acarsdata.arrname+'('+marker.acarsdata.arricao+')</span>';
	aptmarkers[1]= createMarker({lat:marker.acarsdata.arrlat, lng: marker.acarsdata.arrlng}, markerOptions, arrdet);
	map.addOverlay(aptmarkers[1]);
};

function clearMap()
{
	GEvent.clearListeners(map, 'click');
	if(markers.length > 0) 
	{
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