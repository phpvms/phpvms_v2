<h3>View Pilot Report</h3>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Information</th>
	<th>Additional Details</th>
	<th>Status</th>
</tr>
</thead>
<tbody>
	<tr>
		<td align="left" width="33%" nowrap>
			<strong>Flight: </strong><?=$pirep->code . $report->flightnum; ?><br />
			<strong>Departure Airport: </strong><?=$pirep->depname?> (<?=$pirep->depicao; ?>)<br />
			<strong>Arrival Airport: </strong><?=$pirep->arrname?> (<?=$pirep->arricao; ?>)<br />
			<strong>Flight Time: </strong> <?=$pirep->flighttime; ?><br />
			<strong>Date Submitted: </strong> <?=date(DATE_FORMAT, $pirep->submitdate);?><br />
		</td>
		<td align="left" width="33%">
			<?php
			if(!$fields)
			{
				echo 'No additional data found';
			}
			else
			{
				foreach ($fields as $field)
				{
			?>		<strong><?=$field->title ?>:</strong> <?=$field->value ?><br />
			<?php
				}
			}
			?>
			
		</td>
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