<div>
<?php
for($i=0;$i<27;$i++)
{
	echo '<a href="?admin=viewpilots&letter='.$allletters[$i].'">'.$allletters[$i].'</a> ';
}
?>
</div>

<table id="tabledlist">
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
	<td><?=$pilot->email; ?></td>
	<td><?=$pilot->location; ?></td>
	<td><?=$pilot->totalflights; ?></td>
	<td><?=$pilot->totalhours; ?></td>
	<td><?=$pilot->confirmed; ?></td>
	<td><?=$pilot->retired; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>