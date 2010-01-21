<script type="text/javascript">
<?php 
/**
 * These are some options for the ACARS map, you can change here
 * 
 * By default, the zoom level and center are ignored, and the map 
 * will try to fit the all the flights in. If you want to manually set
 * the zoom level and center, set "autozoom" to false.
 * 
 * You can use these MapTypeId's:
 * http://code.google.com/apis/maps/documentation/v3/reference.html#MapTypeId
 * 
 * Change the "TERRAIN" to the "Constant" listed there - they are case-sensitive
 * 
 * Also, how to style the acars pilot list table. You can use these style selectors:
 * 
 * table.acarsmap { }
 * table.acarsmap thead { }
 * table.acarsmap tbody { }
 * table.acarsmap tbody tr.even { }
 * table.acarsmap tbody tr.odd { } 
 */
?>
var acars_map_defaults = {
	autozoom: true,
	zoom: 4,
    center: new google.maps.LatLng(<?php echo Config::Get('MAP_CENTER_LAT'); ?>, <?php echo Config::Get('MAP_CENTER_LNG'); ?>),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    refreshTime: 6000 // In seconds, times 1000
};
</script>
<div class="mapcenter" align="center">
	<div id="acarsmap" style="width:<?php echo  Config::Get('MAP_WIDTH');?>; height: <?php echo Config::Get('MAP_HEIGHT')?>"></div>
</div>
<table border = "0" width="100%" class="acarsmap">
<thead>
	<tr>
		<td><b>Pilot</b></td>
		<td><b>Flight Number</b></td>
		<td><b>Departure</b></td>
		<td><b>Arrival</b></td>
		<td><b>Status</b></td>
		<td><b>Altitude</b></td>
		<td><b>Speed</b></td>
		<td><b>Distance/Time Remain</b></td>
	</tr>
</thead>
<tbody id="pilotlist"></tbody>
</table>
<script type="text/javascript" src="<?php echo fileurl('/lib/js/acarsmap.js');?>"></script>