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
	<th>Name/Type</th>	
	<th>Full Name</th>
	<th>Registration</th>
	<th align="center">Max Pax</th>
	<th align="center">Max Cargo</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allaircraft as $aircraft)
{
?>
<tr class="<?php echo ($aircraft->enabled==0)?'disabled':''?>">
	<td align="center"><?php echo $aircraft->icao; ?></td>
	<td align="center"><?php echo $aircraft->name; ?></td>
	<td align="center"><?php echo $aircraft->fullname; ?></td>
	<td align="center"><?php echo $aircraft->registration; ?></td>
	<td align="center"><?php echo $aircraft->maxpax; ?></td>
	<td align="center"><?php echo $aircraft->maxcargo; ?></td>
	<td align="center" width="1%" nowrap>
			<a href="<?php echo SITE_URL?>/admin/index.php/operations/editaircraft?id=<?php echo $aircraft->id;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/options.png" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>