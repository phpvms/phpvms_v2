<h3>Route Map</h3>
<div class="mapcenter" align="center">
	<div id="routemap" style="width:<?php echo  Config::Get('MAP_WIDTH');?>; height: <?php echo Config::Get('MAP_HEIGHT')?>"></div>
</div>
<?php
/**
 * 
 * This is the new Google Maps v3 code. Be careful of changing
 * things here, only do something if you know what you're doing.
 * 	          
 * These are some options for the ACARS map, you can change here
 * 
 * By default, the zoom level and center are ignored, and the map 
 * will try to fit the all the flights in. If you want to manually set
 * the zoom level and center, set "autozoom" to false.
 * 
 * If you want to adjust the size of the map - Look at the above
 * "routemap" div with the CSS width/height parameters. You can 
 * easily adjust it from there.
 * 
 * And for reference, you want to tinker:
 * http://code.google.com/apis/maps/documentation/v3/basics.html
 */
 
if(isset($pirep))
	$mapdata = $pirep;
if(isset($schedule))
	$mapdata = $schedule;

$centerlat = ($mapdata->deplat + $mapdata->arrlat) / 2;
$centerlng = ($mapdata->deplong + $mapdata->arrlong) / 2;

?>
<script type="text/javascript">
var options = {
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("routemap"), options);

var dep_location = new google.maps.LatLng(<?php echo $mapdata->deplat?>,<?php echo $mapdata->deplong;?>);
var arr_location = new google.maps.LatLng(<?php echo $mapdata->arrlat?>,<?php echo $mapdata->arrlong;?>);

var depMarker = new google.maps.Marker({
	position: dep_location,
	map: map,
	icon: depicon,
	title: "<?php echo $mapdata->depname;?>"
});

var arrMarker = new google.maps.Marker({
	position: arr_location,
	map: map,
	icon: arricon,
	title: "<?php echo $mapdata->arrname;?>"
});

var flightPath = new google.maps.Polyline({
	path: [dep_location, arr_location],
	strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
}).setMap(map);

// Resize the view to fit it all in
map.fitBounds(new google.maps.LatLngBounds(dep_location, arr_location)); 
</script>