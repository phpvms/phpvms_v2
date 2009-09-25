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
var dep_marker;
var arr_marker;
var polyline;
var url = window.location.href.split("index.php")[0];

$(document).ready(function()
{
	var map_options = {
		zoom: 2,
		center: new google.maps.LatLng(map_center_lat, map_center_lng),
		mapTypeId:  google.maps.MapTypeId.TERRAIN, 
		scaleControl: true,
    };

	map = new google.maps.Map(document.getElementById("acarsmap"), map_options);

	liveRefresh();
	//setInterval(function () { liveRefresh(); }, 60000);	
});

function liveRefresh()
{	
	$.ajax({
		type: "GET",
		url: url+ "/action.php/acars/data",
		success: function (data) { processList(data); },
		error: function (x, s, t) { /*console.log(s); console.log(t);*/ },
		dataType: "json"
	});
}

function processList(data)
{
	var latLng;
	
	for(var i = 0; i<data.length; i++)
	{
		// Info window
		htmlstring = data[i].depicao+" to "+data[i].arricao;
		
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(data[i].lat, data[i].lng), 
			map: map, acData: data[i],
			icon: url+"/lib/images/inair.png"
		});
		
		marker.attachInfoWindow({content: htmlstring});
		
		// Form the data
		var pilotlink='<a href="#" onClick="return false;">' + data[i].pilotid+' '+data[i].pilotname + '</a>';
		var row = "<tr><td>"+pilotlink+"</td><td>"+data[i].flightnum+"</td><td>"+data[i].depicao+"</td><td>"+data[i].arricao+"</td><td>"+data[i].phase+"</td><td>"+data[i].alt+"</td><td>"+data[i].gs+"</td><td>"+data[i].distremain+"mi/"+data[i].timeremain+"min </td></tr>";
		$("#pilotlist").append(row);	
	}

}

function draw_route (data, fp_data) {

	// Polyline
	clear_selected_route();
	var fp_coords = [
		new google.maps.LatLng(data.depapt.lat, data.depapt.lng),
		new google.maps.LatLng(fp_data.lat, fp_data.lng),
		new google.maps.LatLng(data.arrapt.lat, data.arrapt.lng)
	];
	
	polyline = new google.maps.Polyline({
		path: fp_coords,
		strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
	});

	polyline.setMap(map);
	
	// Icons
	dep_marker = new google.maps.Marker({
		position: new google.maps.LatLng(data.depapt.lat, data.depapt.lng), 
		map: map, icon: url+"/lib/images/towerdeparture.png"
	});
	
	arr_marker = new google.maps.Marker({
		position: new google.maps.LatLng(data.arrapt.lat, data.arrapt.lng), 
		map: map, icon: url+"/lib/images/towerarrival.png"
	});
}

function clear_selected_route()
{
	if(polyline != undefined)
	{
		polyline.setMap(null);
	}
	
	if(dep_marker != undefined)
	{
		dep_marker.setMap(null);
	}
	
	if(arr_marker != undefined)
	{
		arr_marker.setMap(null);
	}
}

/**
 * attachInfoWindow() binds InfoWindow to a Marker 
 * Creates InfoWindow instance if it does not exist already 
 * @extends Marker
 * @param InfoWindow options
 * @author Esa 2009
 */
google.maps.Marker.prototype.attachInfoWindow = function (options){
	var map_ = this.getMap();
	var ac_data = this.acData;
	map_.bubble_ = map_.bubble_ || new google.maps.InfoWindow();
	google.maps.event.addListener(this, 'click', function () {
		map_.bubble_.setOptions(options);
		map_.bubble_.open(map_, this);
		
		// Chart flight path
		$.ajax({
			type: "GET",
			url: url+"/action.php/acars/routeinfo?depicao="+this.acData.depicao+"&arricao="+this.acData.arricao,
			success: function (data) { draw_route(data, ac_data); },
			error: function (x, s, t) { /*console.log(s); console.log(t);*/ },
			dataType: "json"
		});
		
	});
	
	map_.infoWindowClickShutter = map_.infoWindowClickShutter || 
	google.maps.event.addListener(map_, 'click', function () {
		clear_selected_route();
		map_.bubble_.close();
	});
}

/**
 * accessInfoWindow()
 * @extends Map
 * @returns {InfoWindow} reference to the InfoWindow object instance
 * Creates InfoWindow instance if it does not exist already 
 * @author Esa 2009
 */
google.maps.Map.prototype.accessInfoWindow = function (){
  this.bubble_ = this.bubble_ || new google.maps.InfoWindow();
  return this.bubble_;
}



//	GDownloadUrl(url+"/action.php/xml/acarsdata?rand="+rand, 
//	 function(data) 
//	 {
//        var xml = GXml.parse(data);
//        var aircraft = xml.documentElement.getElementsByTagName("aircraft");
//        markers.length=0;
//        $("#pilotlist").html('');
//         
//        for(var i=0; i<aircraft.length; i++) 
//        {
//			var lat = parseFloat(aircraft[i].getAttribute("lat"));
//			var lng = parseFloat(aircraft[i].getAttribute("lng"))
//			var flightnum = aircraft[i].getAttribute("flightnum");
//			
//     		var point = new GLatLng(lat, lng);
//     		
//			//icon = markers[i].childNodes[0].textContent;
//			var icon = GXml.value(aircraft[i].getElementsByTagName("icon")[0]);
//			var details = GXml.value(aircraft[i].getElementsByTagName("details")[0]);
//			var pilotid = GXml.value(aircraft[i].getElementsByTagName("pilotid")[0]);
//			var pilotname = GXml.value(aircraft[i].getElementsByTagName("pilotname")[0]);
//			var phase = GXml.value(aircraft[i].getElementsByTagName("phase")[0]);
//			var alt = GXml.value(aircraft[i].getElementsByTagName("alt")[0]);
//			var gs = GXml.value(aircraft[i].getElementsByTagName("gs")[0]);
//			var distremain = GXml.value(aircraft[i].getElementsByTagName("distremain")[0]);
//			var timeremain = GXml.value(aircraft[i].getElementsByTagName("timeremain")[0]);
//			var depicao = GXml.value(aircraft[i].getElementsByTagName("depicao")[0]);
//			var arricao = GXml.value(aircraft[i].getElementsByTagName("arricao")[0]);
//			
//			var statusIcon= new GIcon(G_DEFAULT_ICON);
//			statusIcon.image = icon;
//			statusIcon.iconSize = new GSize(35,35);
//			markerOptions = { icon:statusIcon };
//			
//			var pilotlink='<a href="#" onClick="return triggerInfoBubble(' + markers.length + ');">' + pilotid+' '+pilotname + '</a>';
//					
//			var row = "<tr><td>"+pilotlink+"</td><td>"+flightnum+"</td><td>"+depicao+"</td><td>"+arricao+"</td><td>"+phase+"</td><td>"+alt+"</td><td>"+gs+"</td><td>"+distremain+"mi/"+timeremain+"min </td></tr>";
//			$("#pilotlist").append(row);	

//			markers[markers.length]= createMarker(point, markerOptions, depicao, arricao, details);
//			map.addOverlay(markers[markers.length-1]);
//        }
//    });
//};

//function triggerInfoBubble(marker_id)
//{
//	GEvent.trigger(markers[marker_id], 'click');
//	return false;
//}

//function createMarker(point, markerOptions, depicao, arricao, details)
//{
//	var marker = new GMarker(point, markerOptions);
//	
//	GEvent.addListener(marker, "click", 
//		function() { 
//		
//			if(activeMarker == marker)
//				return;
//			else
//				activeMarker = marker;
//				
//			marker.openInfoWindowHtml(details); 				
//			if(depicao != '' && arricao != '')
//			{
//				clickFlight(marker, depicao, arricao); 
//			}
//		});
//			
//	return marker;
//};

//function clickFlight(marker, depicao, arricao)
//{			
//	clearFlightDetails();
//	var url = window.location.href.split("index.php")[0];
//	
//	GDownloadUrl(url+"/action.php/xml/routeinfo?depicao="+depicao+"&arricao="+arricao, 
//	 function(data) 
//	 {
//		if(data == '') return;
//		var xml = GXml.parse(data);
//		
//        var departure = xml.documentElement.getElementsByTagName("departure");
//        var arrival = xml.documentElement.getElementsByTagName("arrival");
//        
//        if(departure == null || arrival == null)
//			return;
//			
//        var depicao = departure[0].getAttribute("icao");
//        var depname = departure[0].getAttribute("name");
//        var depctry = departure[0].getAttribute("country");
//        var deplat = departure[0].getAttribute("lat");
//        var deplng = departure[0].getAttribute("lng");
//        
//        var arricao = arrival[0].getAttribute("icao");
//        var arrname = arrival[0].getAttribute("name");
//        var arrctry = arrival[0].getAttribute("country");
//        var arrlat = arrival[0].getAttribute("lat");
//        var arrlng = arrival[0].getAttribute("lng");
//        
//        // Add poly line
//		var polyOptions = {geodesic:true};
//		polyline = new GPolyline([
//			  new GLatLng(deplat,deplng),
//			  new GLatLng(arrlat, arrlng)
//		  ], "#ff0000", 3, 1, polyOptions);
//		  
//		map.addOverlay(polyline);
//		
//		// Add the airport markers
//		
//		// Departure
//		var depIcon = new GIcon(G_DEFAULT_ICON);
//		depIcon.image = url + '/lib/images/towerdeparture.png';
//		depIcon.iconSize = new GSize(35,35);
//		markerOptions = { icon:depIcon };
//     	var point = new GLatLng(deplat, deplng);
//     	var depdet = '<span style="font-size: 10px">'+depname+'('+depicao+')<br /><b>Country:</b>'+depctry+'</span>';
//		aptmarkers[0]= createMarker(point, markerOptions, '', '', depdet);
//		map.addOverlay(aptmarkers[0]);
//		
//		// Arrival
//		var arrIcon= new GIcon(G_DEFAULT_ICON);
//		arrIcon.image = url + '/lib/images/towerarrival.png';;
//		arrIcon.iconSize = new GSize(35,35);
//		markerOptions = { icon:arrIcon };
//     	var point = new GLatLng(arrlat, arrlng);
//		var arrdet = '<span style="font-size: 10px">'+arrname+'('+arricao+')<br /><b>Country:</b>'+arrctry+'</span>';
//		aptmarkers[1]= createMarker(point, markerOptions, '', '', arrdet);
//		map.addOverlay(aptmarkers[1]);
//	 });
//	 
//};

//function clearMap()
//{
//	GEvent.clearListeners(map, 'click');
//	
//	if(markers.length > 0) {
//		for(var i = 0; i < markers.length; i++) {
//			map.removeOverlay(markers[i]);
//		}
//	}
//	markers.length = 0;
//}

//function clearFlightDetails()
//{
//	if(aptmarkers.length > 0) 
//	{
//		map.removeOverlay(polyline);
//		
//		for(var i = 0; i < aptmarkers.length; i++) {
//			map.removeOverlay(aptmarkers[i]);
//		}
//	}
//	aptmarkers.length = 0;
//}