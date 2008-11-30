<h3>Pilots List</h3>
<div align="center">
	<a href="?admin=viewpilots&letter=">View All</a>
<?php
for($i=0;$i<27;$i++)
{
	echo '<a href="?admin=viewpilots&letter='.$allletters[$i].'">'.$allletters[$i].'</a> ';
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
	<td><a href="index.php?admin=viewpilots&action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>"><?php echo $pilot->firstname . ' ' . $pilot->lastname; ?></a></td>
	<td align="center"><?php echo $pilot->email; ?></td>
	<td align="center"><?php echo $pilot->location; ?></td>
	<td align="center"><?php echo $pilot->totalflights; ?></td>
	<td align="center"><?php echo $pilot->totalhours; ?></td>
	<td align="center"><a href="index.php?admin=viewpilots&action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>"><img src="lib/images/options.gif" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>