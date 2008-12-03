<h3>Schedules</h3>
<?php
if(!$schedules)
{
	echo '<p id="error">No schedules have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Aircraft</th>
	<th>Distance</th>
	<th>Times Flown</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($schedules as $sched)
{
?>
<tr id="row<?php echo $sched->id?>">
	<td align="left"><?php echo $sched->code . $sched->flightnum; ?> (Leg <?php echo$sched->leg?>)</td>
	<td align="left"><?php echo $sched->depicao; ?> (<?php echo $sched->deptime;?>)</td>
	<td align="left"><?php echo $sched->arricao; ?> (<?php echo $sched->arrtime;?>)</td>
	<td align="center"><?php echo $sched->aircraft; ?></td>
	<td align="center"><?php echo $sched->distance; ?></td>
	<td align="center"><?php echo $sched->timesflown; ?></td>
	<td align="center">
		<a href="?admin=editschedule&id=<?php echo $sched->id;?>"><img src="lib/images/edit.gif" alt="Edit Schedule" /></a>
	<?php
	/*
	  <a href="action.php?admin=<?php echo Vars::GET('admin'); ?>"
			class="deleteitem" action="deleteschedule"
			id="<?php echo $sched->id;?>"><img src="lib/images/delete.gif" alt="Delete" /></a>
	 */ ?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>