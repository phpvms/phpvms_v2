<h3>View Pilot Report</h3>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>	
	<th>Arrival</th>
	<th>Flight Time</th>
	<th>Submitted</th>
	<th>Status</th>
</tr>
</thead>
<tbody>
	<tr>
		<td align="center"><?=$pirep->code . $report->flightnum; ?></td>
		<td align="center"><?=$pirep->depicao; ?></td>
		<td align="center"><?=$pirep->arricao; ?></td>
		<td align="center"><?=$pirep->flighttime; ?></td>
		<td align="center"><?=date(DATE_FORMAT, $pirep->submitdate); ?>
		<td align="center">
		<?php
			if($pirep->accepted == PIREP_ACCEPTED)
				echo '<div id="success">Accepted</div>';
			elseif($pirep->accepted == PIREP_REJECTED)
				echo '<div id="error">Rejected</div>';
			elseif($pirep->accepted == PIREP_PENDING)
				echo '<div id="error">Approval Pending</div>';
			elseif($pirep->accepted == PIREP_INPROGRESS)
				echo '<div id="error">Flight in Progress</div>';
		?>
	</td>
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
?>