<h3>Schedules</h3>
<p>The flight routes you currently serve.</p>
<?php
// id="dialog" class="jqModal" 
?>
<p><a href="index.php?admin=addschedule">Add a schedule</a></p>
<br />
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
<tr>
	<td align="center"><?php echo $sched->code . $sched->flightnum; ?></td>
	<td align="center"><?=$sched->depicao; ?>(<?=$sched->deptime;?>)</td>
	<td align="center"><?=$sched->arricao; ?>(<?=$sched->arrtime;?>)</td>
	<td align="center"><?=$sched->aircraft; ?></td>
	<td align="center"><?=$sched->distance; ?></td>
	<td align="center"><?=$sched->timesflown; ?></td>
	<td align="center">
		<a id="dialog" class="jqModal" href="action.php?admin=schedules&action=viewroute&id=<?=$sched->id;?>">Route</a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>