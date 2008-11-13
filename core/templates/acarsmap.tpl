<script type="text/javascript" src="<?=SITE_URL?>/lib/js/acarsmap.js"></script>
<div class="mapcenter" align="center">
	<div id="acarsmap" style="width:<?= Config::Get('MAP_WIDTH');?>; height: <?=Config::Get('MAP_HEIGHT')?>"></div>
<p style="width:<?= Config::Get('MAP_WIDTH');?>;font-size: 10px; text-align: center;">The map and table automatically update. <span style="color:red;">Red</span> indicates pilot is on the ground. <span style="color:green;">Green</span> indicates in air. Click pilot name to view.</p>
</div>
<table border = "0" width="100%">
<thead>
	<tr>
		<td><b>Pilot</b></td><td><b>Flight Number</b></td><td><b>Departure</b></td><td><b>Arrival</b></td><td><b>Status</b></td><td><b>Altitude</b></td><td><b>Speed</b></td><td><b>Distance/Time Remain</b></td>
	</tr>
</thead>
<tbody id="pilotlist" ></tbody>
</table>