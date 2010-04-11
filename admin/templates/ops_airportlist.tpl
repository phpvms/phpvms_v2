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
<tr class="<?php if($airport->hub==1) { echo 'background_yellow'; } ?>">
	<td align="center"><?php if($airport->hub==1) { echo '<strong>'; } echo $airport->icao; if($airport->hub==1) { echo '</strong>'; }  ?></td>
	<td><?php if($airport->hub==1) { echo '<strong>'; } echo $airport->name; if($airport->hub==1) { echo '</strong>'; }  ?></td>
	<td align="center"><?php echo $airport->country; ?></td>
	<td align="center"><?php echo $airport->lat; ?></td>
	<td align="center"><?php echo $airport->lng; ?></td>
	<td align="center"><?php echo  $airport->fuelprice == 0 ? 'live' : $airport->fuelprice; ?></td>
	<td align="center" width="1%" nowrap>
		<button id="dialog" class="jqModal {button:{icons:{primary:'ui-icon-wrench'}}}" href="<?php echo SITE_URL?>/admin/action.php/operations/editairport?icao=<?php echo $airport->icao?>">Edit</button></td>
</tr>
<?php
}
?>
</tbody>
</table>