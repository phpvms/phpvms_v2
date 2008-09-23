<h3>Flight <?=$pirep->code . $pirep->flightnum; ?></h3>
<table width="100%">
	<tr>
		<td><strong>Departure Airport: </strong><br />
			<?=$pirep->depname?> (<?=$pirep->depicao; ?>)</td>
		<td><strong>Arrival Airport: </strong><br />
			<?=$pirep->arrname?> (<?=$pirep->arricao; ?>)</td>
	</tr>
</table>

<h3>Flight Details</h3>
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
						
<strong>Flight Time: </strong> <?=$pirep->flighttime; ?><br />
<strong>Date Submitted: </strong> <?=date(DATE_FORMAT, $pirep->submitdate);?><br />
<?php
if(!$fields)
{
	//echo 'No additional data found';
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