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
<tr id="row<?=$sched->id?>">
	<td align="center"><?php echo $sched->code . $sched->flightnum; ?></td>
	<td align="center"><?=$sched->depicao; ?> (<?=$sched->deptime;?>)</td>
	<td align="center"><?=$sched->arricao; ?> (<?=$sched->arrtime;?>)</td>
	<td align="center"><?=$sched->aircraft; ?></td>
	<td align="center"><?=$sched->distance; ?></td>
	<td align="center"><?=$sched->timesflown; ?></td>
	<td align="center">
		<a href="?admin=editschedule&id=<?=$sched->id;?>"><img src="lib/images/edit.gif" alt="Edit Schedule" /></a>
		<br />
        <a href="action.php?admin=<?=Vars::GET('admin'); ?>"
			class="deleteitem" action="deleteschedule"
			id="<?=$sched->id;?>"><img src="lib/images/delete.gif" alt="Delete" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>