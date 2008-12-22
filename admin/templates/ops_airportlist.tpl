<h3>Airports List</h3>
<p>The airports that are currently served are listed here.</p>
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
	<td align="center"><?php echo $airport->icao; ?></td>
	<td ><?php echo $airport->name; ?></td>
	<td align="center"><?php echo $airport->country; ?></td>
	<td align="center"><?php echo $airport->lat; ?></td>
	<td align="center"><?php echo $airport->lng; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="a<?php echo SITE_URL?>/admin/index.php/operations/editairport?icao=<?php echo $airport->icao?>"><img src="lib/images/edit.gif" alt="Edit" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>