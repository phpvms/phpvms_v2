<script type="text/javascript">
map_zoom_level = <?php echo Config::Get('MAP_ZOOM_LEVEL'); ?>;
map_center_lat = "<?php echo Config::Get('MAP_CENTER_LAT'); ?>";
map_center_lng = "<?php echo Config::Get('MAP_CENTER_LNG'); ?>";
map_type = <?php echo Config::Get('MAP_TYPE'); ?>;
</script>
<div class="mapcenter" align="center">
	<div id="acarsmap" style="width:<?php echo  Config::Get('MAP_WIDTH');?>; height: <?php echo Config::Get('MAP_HEIGHT')?>"></div>
<p style="width:<?php echo  Config::Get('MAP_WIDTH');?>;font-size: 10px; text-align: center;">The map and table automatically update. <span style="color:red;">Red</span> indicates pilot is on the ground. <span style="color:green;">Green</span> indicates in air. Click pilot name to view.</p>
</div>
<table border = "0" width="100%">
<thead>
	<tr>
		<td><b>Pilot</b></td><td><b>Flight Number</b></td><td><b>Departure</b></td><td><b>Arrival</b></td><td><b>Status</b></td><td><b>Altitude</b></td><td><b>Speed</b></td><td><b>Distance/Time Remain</b></td>
	</tr>
</thead>
<tbody id="pilotlist" ></tbody>
</table>
<script type="text/javascript" src="<?php echo fileurl('/lib/js/acarsmap.js');?>"></script>