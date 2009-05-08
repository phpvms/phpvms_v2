<h3>Airports List</h3>
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
	<th>Fuel Cost</th>
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
	<td><?php if($airport->hub==1) { echo '<strong>'; } echo $airport->name; if($airport->hub==1) { echo '</strong>'; }  ?></td>
	<td align="center"><?php echo $airport->country; ?></td>
	<td align="center"><?php echo $airport->lat; ?></td>
	<td align="center"><?php echo $airport->lng; ?></td>
	<td align="center"><?php echo $airport->fuelprice; ?></td>
	<td align="center" width="1%" nowrap><a id="dialog" class="jqModal" href="<?php echo SITE_URL?>/admin/action.php/operations/editairport?icao=<?php echo $airport->icao?>"><img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>