<h3>Pilot Reports</h3>

<h3>Pending</h3>
<?php
if(!$pending)
	echo '<p>No flight reports pending</p>';
else
{
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight</th>
	<th>Departure</th>	
	<th>Arrival</th>
	<th>Flight Time</th>
	<th>Date Submitted</th>
</tr>
</thead>
<tbody>
<?php
foreach($pending as $pirep)
{
?>
<tr>
	<td align="center"><a href="?page=viewreport&pirepid=<?=$pirep->id?>"><?=$pirep->code.$pirep->flightnum; ?></a></td>
	<td align="center"><?=$pirep->depicao; ?></td>
	<td align="center"><?=$pirep->arricao; ?></td>
	<td align="center"><?=$pirep->flighttime; ?></td>
	<td align="center"><?=$pirep->submitdate; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>
<?php
}
?>
<h3>Accepted</h3>

<?php
if(!$accepted)
	echo '<p>No accepted flight reports</p>';
else
{
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight</th>
	<th>Departure</th>	
	<th>Arrival</th>
	<th>Flight Time</th>
	<th>Date Submitted</th>
</tr>
</thead>
<tbody>
<?php
foreach($accepted as $pirep)
{
?>
<tr>
	<td align="center"><a href="?page=viewreport&id=<?=$pirep->id?>"><?=$pirep->code.$pirep->flightnum; ?></a></td>
	<td align="center"><?=$pirep->depicao; ?></td>
	<td align="center"><?=$pirep->arricao; ?></td>
	<td align="center"><?=$pirep->flighttime; ?></td>
	<td align="center"><?=$pirep->submitdate; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php
}
?>