<div align="center">
<?php
for($i=0;$i<27;$i++)
{
	echo '<a href="?admin=viewpilots&letter='.$allletters[$i].'">'.$allletters[$i].'</a> ';
}


if(!$allpilots)
{
	echo '<p>There are no pilots!</p>';
	return;
}

?>
</div>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot Name</th>
	<th>Email Address</th>
	<th>Location</th>
	<th>Total Flights</th>
	<th>Total Hours</th>
	<th>Confirmed</th>
	<th>Retired</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpilots as $pilot)
{
?>
<tr>
	<td><?php echo $pilot->firstname . ' ' . $pilot->lastname; ?></td>
	<td align="center"><?=$pilot->email; ?></td>
	<td align="center"><?=$pilot->location; ?></td>
	<td align="center"><?=$pilot->totalflights; ?></td>
	<td align="center"><?=$pilot->totalhours; ?></td>
	<td align="center"><a id="dialog" href="?admin=viewpilots&action=viewoptions&id=<?=$pilot->userid;?>">Options</a></td>
</tr>
<?php
}
?>
</tbody>
</table>