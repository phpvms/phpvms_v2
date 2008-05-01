<h3>Airport's List</h3>
<p>The airports that are currently served are listed here.</p>
<p><a id="dialog" class="jqModal" href="action.php?admin=addairport"><img src="lib/images/addairport.gif" alt="Add Airport" /></a></p>
<?php
if(!$airports)
{
	echo '<p id="error">No airports have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>ICAO</th>
	<th>Airport Name</th>
	<th>Airport Country</th>
	<th>Latitude</th>
	<th>Longitude</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($airports as $airport)
{
?>
<tr>
	<td align="center"><?=$airport->icao; ?></td>
	<td align="center"><?=$airport->name; ?></td>
	<td align="center"><?=$airport->country; ?></td>
	<td align="center"><?=$airport->lat; ?></td>
	<td align="center"><?=$airport->lng; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=airports&action="><img src="lib/images/options.gif" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>