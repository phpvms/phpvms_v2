<h3>Search Schedules</h3>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Aircraft</th>
	<th>Distance</th>
	<th>Times Flown</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<tr>
	<td align="center"><?=$depairports; ?></td>
	<td align="center"><?=$sched->arricao; ?>(<?=$sched->arrtime;?>)</td>
	<td align="center"><?=$sched->aircraft; ?></td>
	<td align="center"><?=$sched->distance; ?></td>
	<td align="center"><?=$sched->timesflown; ?></td>
	<td align="center">
		<a id="dialog" class="jqModal" href="action.php?admin=schedules&action=viewroute&id=<?=$sched->id;?>">Route</a>
	</td>
</tr>
</tbody>
</table>
<hr>