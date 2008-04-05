<h3>View Pilot Report</h3>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>	
	<th>Arrival</th>
	<th>Flight Time</th>
	<th>Submitted</th>
</tr>
</thead>
<tbody>
	<tr>
		<td align="center"><?=$report->code . $report->flightnum; ?></td>
		<td align="center"><?=$report->depicao; ?></td>
		<td align="center"><?=$report->arricao; ?></td>
		<td align="center"><?=$report->flighttime; ?></td>
		<td align="center"><?=date(DATE_FORMAT, $report->submitdate); ?>
	</tr>
</tbody>
</table>

<?php
if($comments)
{
	echo '<h3>Comments</h3>
		<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Commenter</th>
	<th>Comment</th>	
</tr>
</thead>
<tbody>';
	
	foreach($comments as $comment)
	{
?>
	<tr>
		<td width="15%" nowrap><?=$comment->firstname . ' ' .$comment->lastname?></td>
		<td align="left"><?=$comment->comment?></td>
	</tr>
<?php
	}
	
	echo '</tbody></table>';
}

Template::Show('pirep_map.tpl');
?>