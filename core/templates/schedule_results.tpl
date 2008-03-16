<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Aircraft</th>
</tr>
</thead>
<tbody>
<?php
foreach($allroutes as $route)
{
?>
<tr>
	<td align="center"><?php echo $route->code . $route->flightnum; ?></td>
	<td align="center"><?=$route->depicao; ?>(<?=$route->deptime;?>)</td>
	<td align="center"><?=$route->arricao; ?>(<?=$route->arrtime;?>)</td>
	<td align="center"><?=$route->aircraft; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>