<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Route</th>
	<th>Aircraft</th>
	<th>Departure Time</th>
	<th>Arrival Time</th>
</tr>
</thead>
<tbody>
<?php
foreach($allroutes as $route)
{
?>
<tr>
	<td align="center"><?=$route->code . $route->flightnum; ?> Leg <?=$route->leg?></td>
	<td align="center"><?=$route->depname?> (<?=$route->depicao; ?>) to <?=$route->arrname?> (<?=$route->arricao; ?>)</td>
	<td align="center"><?=$route->aircraft; ?></td>
	<td><?=$route->deptime;?></td>
	<td><?=$route->arrtime;?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>