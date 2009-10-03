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

$(document).ready(function()
{
	// Only turn on ACARS Map if there's an acarsmap div present
	if(map_zoom_level == undefined)
	{
		map_zoom_level = 2;
	}
	
	var has_acars_div = document.getElementById("acarsmap");
	if(has_acars_div != undefined  && has_acars_div != null)
	{
		var map_options = {
			zoom: map_zoom_level, center: new google.maps.LatLng(map_center_lat, map_center_lng),
			mapTypeId:  google.maps.MapTypeId.TERRAIN, scaleControl: true,
		};
		map = new google.maps.Map(document.getElementById("acarsmap"), map_options);
		liveRefresh();

		//setInterval(function () { liveRefresh(); }, 60000);	
	}
});

function liveRefresh()
{	
	$.ajax({ type: "GET", url: url+ "/action.php/acars/data",
			 success: function (data) { processList(data); }, 
			 dataType: "json" });
}

function processList(data)
{
	var latLng;
	
	for(var i = 0; i<data.length; i++)
	{
		// Info window
		// console.log(data[i]);
		if(data[i].distremain == "" || data[i].distremain == null)
			data[i].distremain == '-';
			
		if(data[i].timeremaining == "" || data[i].timeremaining == null)
			data[i].timeremaining == '-';
		
		htmlstring = '<span style="font-size: 10px; text-align:left; width: 100%" align="left">'
		+'<a href="'+url+'/index.php/profile/view/'+data[i].pilotid+'">'+data[i].pilotid+' - '+data[i].firstname+' '+data[i].lastname+'</a><br />'
		+'<strong>Flight '+data[i].flightnum+'</strong> ('+data[i].depicao+' to '+data[i].arricao+')<br />'
		+'<strong>Status: </strong>'+data[i].phasedetail+'<br />'
		+'<strong>Dist/Time Remain: </strong>'+data[i].distremain+'/'+data[i].timeremaining+' h:m<br />'
		+'</span>'
		
		var row = "<tr><td>"+'<a href="#" onClick="return false;">' + data[i].pilotid+' '+data[i].pilotname + '</a>'+"</td>"
			+"<td>"+data[i].flightnum+"</td><td>"+data[i].depicao+"</td><td>"+data[i].arricao+"</td>"
			+"<td>"+data[i].phasedetail+"</td><td>"+data[i].alt+"</td><td>"+data[i].gs+"</td>"
			+"<td>"+data[i].distremain+"mi/"+data[i].timeremaining+"min </td></tr>";
		
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(data[i].lat, data[i].lng), 
			map: map, acData: data[i], icon: url+"/lib/images/inair.png"
		});
		
		marker.attachInfoWindow({content: htmlstring});
		$("#pilotlist").append(row);	
	}

}

function draw_route (data, fp_data) 
{
	clear_selected_route();
	
	// Polyline
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
		polyline.setMap(null);
	
	if(dep_marker != undefined)
		dep_marker.setMap(null);
	
	if(arr_marker != undefined)
		arr_marker.setMap(null);
}