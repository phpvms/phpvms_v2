<h3>My Flight Bids</h3>
<?php
if(!$bids)
{
	echo '<p align="center">You have not bid on any flights</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Route</th>
	<th>Aircraft</th>
	<th>Departure Time</th>
	<th>Arrival Time</th>
	<th>Distance</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($bids as $bid)
{
?>
<tr id="bid<?php echo $bid->bidid ?>">
	<td><?php echo $bid->code . $bid->flightnum; ?></td>
	<td align="center"><?php echo $bid->depicao; ?> to <?php echo $bid->arricao; ?></td>
	<td align="center"><?php echo $bid->aircraft; ?> (<?php echo $bid->registration?>)</td>
	<td><?php echo $bid->deptime;?></td>
	<td><?php echo $bid->arrtime;?></td>
	<td><?php echo $bid->distance;?></td>
	<td><a href="<?php echo SITE_URL?>/index.php/PIREPS/filepirep/<?php echo $bid->bidid ?>/">File PIREP</a><br />
		<a id="<?php echo $bid->bidid; ?>" class="deleteitem" href="<?php echo SITE_URL?>/action.php/Schedules/removebid/">Remove Bid *</a><br />
		<a href="<?php echo SITE_URL?>/index.php/schedules/brief/<?php echo $bid->id ?>">Pilot Brief</a><br />
		<a href="<?php echo SITE_URL?>/index.php/schedules/boardingpass/<?php echo $bid->id;?>" />Boarding Pass</a>
		
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<p align="right">* - double click</p>
<hr>