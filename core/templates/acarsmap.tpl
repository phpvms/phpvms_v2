<script type="text/javascript" src="<?=SITE_URL?>/lib/js/acarsmap.js"></script>
<table width="100%">
	<tr>
		<td style="width: <?= Config::Get('MAP_WIDTH');?>">
			<div id="acarsmap" style="width:<?= Config::Get('MAP_WIDTH');?>; height: <?=Config::Get('MAP_HEIGHT')?>"></div>
		</td>
		<td align="left" valign="top">
			<h3>Active Pilots:</h3>
			<div id="pilotlist"></div>
		</td>
		</tr>
</table>
