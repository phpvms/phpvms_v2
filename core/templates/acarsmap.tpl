<script type="text/javascript" src="<?=SITE_URL?>/lib/js/acarsmap.js"></script>
<div class="mapcenter" align="center">
	<div id="acarsmap" style="width:<?= Config::Get('MAP_WIDTH');?>; height: <?=Config::Get('MAP_HEIGHT')?>"></div>
<p style="width:<?= Config::Get('MAP_WIDTH');?>;font-size: 10px; text-align: center;">The map and table automatically update. <span style="color:red;">Red</span> indicates pilot is on the ground. <span style="color:green;">Green</span> indicates in air. Click pilot name to view.</p>
</div>
<table id="pilotlist" width="100%">
</table>