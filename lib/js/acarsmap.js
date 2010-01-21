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
 *
 * Rewritten for Google Maps v3
 */

var flightMarkers = [];
var flightPath = null;
var depMarker = null, arrMarker = null;
var info_window= null;
  
var defaultOptions = {
	autozoom: true,
	zoom: 4,
	center: new google.maps.LatLng(-25.363882,131.044922),
	mapTypeId: google.maps.MapTypeId.TERRAIN,
	refreshTime: 6000
};

var options = $.extend({}, defaultOptions, acars_map_defaults);
var map = new google.maps.Map(document.getElementById("acarsmap"), options);

// They clicked the map
google.maps.event.addListener(map, 'click', function()
{
	clearPreviousMarkers();
});

liveRefresh();
setInterval(function () { 
	liveRefresh(); 

}, options.refreshTime);

function liveRefresh()
{
	$.ajax({
		type: "GET",
		url: url + "/action.php/acars/data",
		dataType: "json",
		success: function(data) 
		{
			populateMap(data);
	    }
	});
};

function populateMap(data)
{
	clearMap();
	$("#pilotlist").html("");
	
	if (data.length == 0) {
		return false;
	}

	var lat, lng;
	var details, row, pilotlink;
	var bounds = new google.maps.LatLngBounds();
	
	for (var i = 0; i < data.length; i++) 
	{
		lat = data[i].lat;
		lng = data[i].lng;
		
		pilotlink = '<a href="#">'+data[i].pilotid+' '+data[i].pilotname+'</a>';
		details = '<span style="font-size: 10px; text-align:left; width: 100%" align="left">'
			+'<a href="'+url+'/index.php/profile/view/'+data[i].pilotid+'">'+data[i].pilotid+' - '+data[i].pilotname+'</a><br />'
			+'<strong>Flight '+data[i].flightnum+'</strong> ('+data[i].depicao+' to '+data[i].arricao+')<br />'
			+'<strong>Status: </strong>'+data[i].phasedetail+'<br />'
			+'<strong>Dist/Time Remain: </strong>'+data[i].distremaining+' / '+data[i].timeremaining+' h:m<br />'
			+'</span>';
		
		if(i%2 == 0)
			trclass = "even";
		else
			trclass = "odd";
			
		row = "<tr class=\""+trclass+"\"><td>"+pilotlink+"</td><td>"+data[i].flightnum+"</td><td>"+data[i].depicao+"</td>"
			+"<td>"+data[i].arricao+"</td><td>"+data[i].phasedetail+"</td><td>"+data[i].alt+"</td>"
			+"<td>"+data[i].gs+"</td><td>"+data[i].distremaining+"mi/"+data[i].timeremaining+"</td></tr>";
		
		$("#pilotlist").append(row);
		
		flightMarkers[flightMarkers.length] = new google.maps.Marker({
			position: new google.maps.LatLng(lat, lng),
			map: map,
			icon: url+"/lib/images/inair/"+data[i].heading+".png",
			flightdetails: data[i],
			infowindow_content: details
		});
		
		bounds.extend(flightMarkers[flightMarkers.length - 1].position);
				
		google.maps.event.addListener(flightMarkers[flightMarkers.length - 1], 'click', function() 
		{
			clearPreviousMarkers();
			
			// Flight details info window
			info_window = new google.maps.InfoWindow({ 
				content: this.infowindow_content,
				position: this.position
			});
			
			info_window.open(map, this);
			
			// Add polyline, and start/end points
			var dep_location = new google.maps.LatLng(this.flightdetails.deplat, this.flightdetails.deplng);
			var arr_location = new google.maps.LatLng(this.flightdetails.arrlat, this.flightdetails.arrlng);
			
			depMarker = new google.maps.Marker({
				position: dep_location,
				map: map,
				icon: depicon,
				title: this.flightdetails.depname,
				zIndex: 100
			});

			arrMarker = new google.maps.Marker({
				position: arr_location,
				map: map,
				icon: arricon,
				title: this.flightdetails.arrname,
				zIndex: 100
			});

			flightPath = new google.maps.Polyline({
				path: [dep_location, this.position, arr_location],
				strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
			});
			
			flightPath.setMap(map);
		});
	}
	
	// If they selected autozoom
	if(options.autozoom == true)
	{
		map.fitBounds(bounds); 
	}
}

function clearPreviousMarkers()
{
	if(info_window)
	{
		info_window.close();
		info_window = null;
	}
	
	if(depMarker != null)
	{
		depMarker.setMap(null);
		depMarker = null;
	}
	
	if(arrMarker != null)
	{
		arrMarker.setMap(null);
		arrMarker = null;
	}
	
	if(flightPath != null)
	{
		flightPath.setMap(null);
		flightPath = null;
	}
}

function clearMap()
{
	if(flightMarkers.length > 0)
	{
		for(var i = 0; i < flightMarkers.length; i++) {
			flightMarkers[i].setMap(null);
		}
	}
	
	flightMarkers.length = 0;
}