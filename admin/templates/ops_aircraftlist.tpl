<h3>Aircraft List</h3>
<p>These are all the aircraft that your airline operates.</p>
<?php
if(!$allaircraft)
{
	echo '<p id="error">No aircraft have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>ICAO</th>
	<th>Name</th>	
	<th>Full Name</th>
	<th>Range</th>
	<th>Weight</th>
	<th>Cruise</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allaircraft as $aircraft)
{
?>
<tr>
	<td align="center"><?php echo $aircraft->icao; ?></td>
	<td align="center"><?php echo $aircraft->name; ?></td>
	<td align="center"><?php echo $aircraft->fullname; ?></td>
	<td align="center"><?php echo $aircraft->range; ?></td>
	<td align="center"><?php echo $aircraft->weight; ?></td>
	<td align="center"><?php echo $aircraft->cruise; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=editaircraft&id=<?php echo $aircraft->id;?>"><img src="lib/images/options.gif" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>