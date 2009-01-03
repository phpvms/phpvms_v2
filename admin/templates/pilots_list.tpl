<h3>Pilots List</h3>
<div align="center">
	Find by Last Name: <a href="?admin=viewpilots&letter=">View All</a>
<?php
for($i=65;$i<91;$i++)
{
	echo '<a href="'.SITE_URL.'/admin/index.php/pilotadmin/viewpilots?letter='.chr($i).'">'.chr($i).'</a> ';
}
?>
</div>
<br />
<?php
if(!$allpilots)
{
	echo '<p>There are no pilots!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot Name</th>
	<th>Email Address</th>
	<th>Location</th>
	<th>Total Flights</th>
	<th>Total Hours</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpilots as $pilot)
{
?>
<tr>
	<td><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>"><?php echo $pilot->firstname . ' ' . $pilot->lastname; ?></a></td>
	<td align="center"><?php echo $pilot->email; ?></td>
	<td align="center"><?php echo $pilot->location; ?></td>
	<td align="center"><?php echo $pilot->totalflights; ?></td>
	<td align="center"><?php echo $pilot->totalhours; ?></td>
	<td align="center" width="1%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/options.png" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>