<h3>Route Map</h3>
<div class="mapcenter" align="center">
	<div id="routemap" style="width:<?php echo  Config::Get('MAP_WIDTH');?>; height: <?php echo Config::Get('MAP_HEIGHT')?>"></div>
</div>

<?php
# I do this because they can both contain the coordinates
if($pirep)
	$mapdata = $pirep;
if($schedule)
	$mapdata = $schedule;

# Determine where we should center this. 
$centerlat = ($mapdata->deplat + $mapdata->arrlat) / 2;
$centerlng = ($mapdata->deplong + $mapdata->arrlong) / 2;
?>
<script type="text/javascript">
var map_options = {
	zoom: 4, center: new google.maps.LatLng(<?php echo $centerlat; ?>, <?php echo $centerlng; ?>),
	mapTypeId:  google.maps.MapTypeId.TERRAIN, scaleControl: true,
};

var map = new google.maps.Map(document.getElementById("routemap"), map_options);

// Airport Markers
var dep_marker = new google.maps.Marker({
	position: new google.maps.LatLng(<?php echo $mapdata->deplat?>, <?php echo $mapdata->deplong?>), 
	map: map, icon: url+"/lib/images/towerdeparture.png"
});
dep_marker.attachInfoWindow({content: "<?php echo "$mapdata->depname ($mapdata->depicao)";?>"});

var arr_marker = new google.maps.Marker({
	position: new google.maps.LatLng(<?php echo $mapdata->arrlat?>, <?php echo $mapdata->arrlong?>), 
	map: map, icon: url+"/lib/images/towerarrival.png"
});
arr_marker.attachInfoWindow({content: "<?php echo "$mapdata->arrname ($mapdata->arricao)";?>"});

// Line
var fp_coords = [
	new google.maps.LatLng(<?php echo $mapdata->deplat?>, <?php echo $mapdata->deplong?>),
	new google.maps.LatLng(<?php echo $mapdata->arrlat?>, <?php echo $mapdata->arrlong?>)
];

polyline = new google.maps.Polyline({ path: fp_coords, strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });

polyline.setMap(map);
</script>