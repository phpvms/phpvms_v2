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
var routeMarkers = [];
var flightPath = null;
var depMarker = null, arrMarker = null;
var info_window= null;
var run_once = false;
  
var defaultOptions = {
	autozoom: true,
	zoom: 4,
	center: new google.maps.LatLng(-25.363882,131.044922),
	mapTypeId: google.maps.MapTypeId.TERRAIN,
	refreshTime: 12000,
	autorefresh: true
};

var options = $.extend({}, defaultOptions, acars_map_defaults);
var map = new google.maps.Map(document.getElementById("acarsmap"), options);

// They clicked the map
google.maps.event.addListener(map, 'click', function()
{
	//clearPreviousMarkers();
});

liveRefresh();
if(options.autorefresh == true)
{
    setInterval(function () { liveRefresh(); }, options.refreshTime);
}

function liveRefresh()
{
	$.ajax({
		type: "GET",
		url: url + "/action.php/acars/data",
		dataType: "json",
		cache: false,
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
		if(data[i] == null || data[i].lat == null || data[i].lng == null
		    || data[i].lat == "" || data[i].lng == "")
		{
			continue;
	    }
			
		lat = data[i].lat;
		lng = data[i].lng;
				
		if(i%2 == 0)
			data[i].class = "even";
		else
			data[i].class = "odd";
		
		// Pull ze templates!
		var map_row = tmpl("acars_map_row", {flight: data[i]});
		var detailed_bubble = tmpl("acars_map_bubble", {flight: data[i]});
		
		$('#pilotlist').append(map_row);
		
		var pos = new google.maps.LatLng(lat, lng);
		flightMarkers[flightMarkers.length] = new google.maps.Marker({
			position: pos,
			map: map,
			icon: url+"/lib/images/inair/"+data[i].heading+".png",
			flightdetails: data[i],
			infowindow_content: detailed_bubble
		});
		
		bounds.extend(pos);
				
		google.maps.event.addListener(flightMarkers[flightMarkers.length - 1], 'click', function() 
		{
			clearPreviousMarkers();
			
			var focus_bounds = new google.maps.LatLngBounds();
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
			
			// Now the flight path, if it exists
			var path = new Array();
			path[path.length] = dep_location;
			focus_bounds.extend(dep_location);
			if(this.flightdetails.route_details.length > 0)
			{
		        $.each(this.flightdetails.route_details, function(i, nav)
		        {
		            var loc = new google.maps.LatLng(nav.lat, nav.lng);
    		        
		            if(nav.type == 3)
		                icon = "icon_vor.png";
		            else
		                icon = "icon_fix.png";
    		        
		            var navpoint_info = tmpl("navpoint_bubble", {nav: nav});
		            routeMarkers[routeMarkers.length] = new google.maps.Marker({
			            position: loc,
			            map: map,
			            icon: url + "/lib/images/"+icon,
			            title: nav.title,
			            zIndex: 100,
			            infowindow_content: navpoint_info
		            });
    		        
		            google.maps.event.addListener(routeMarkers[routeMarkers.length - 1], 'click', function() 
				    {
					    info_window = new google.maps.InfoWindow({ 
						    content: this.infowindow_content,
						    position: this.position
					    });
    					
					    info_window.open(map, this);
				    });
    		        
		            path[path.length] = loc;
		            focus_bounds.extend(loc);
		        });
		    }
			
			path[path.length] = arr_location;
			focus_bounds.extend(this.position);
			focus_bounds.extend(arr_location);

			flightPath = new google.maps.Polyline({
				path: path,
				strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
			});
			
			map.fitBounds(focus_bounds); 
			flightPath.setMap(map);
		});
	}
	
	// If they selected autozoom, only do the zoom first time
	if(options.autozoom == true && run_once == false)
	{
		map.fitBounds(bounds); 
		run_once = true;
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
	
	if(routeMarkers.length > 0)
	{
	    for(var i = 0; i < routeMarkers.length; i++) {
			routeMarkers[i].setMap(null);
		}
	}
	
	routeMarkers.length = 0;
	
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
	
	if(routeMarkers.length > 0)
	{
	    for(var i = 0; i < routeMarkers.length; i++) {
			routeMarkers[i].setMap(null);
		}
	}
	
	routeMarkers.length = 0;
}