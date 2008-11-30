<h3>Flight <?php echo $pirep->code . $pirep->flightnum; ?></h3>
<table width="100%">
	<tr>
		<td><strong>Departure Airport: </strong><br />
			<?php echo $pirep->depname?> (<?php echo $pirep->depicao; ?>)</td>
		<td><strong>Arrival Airport: </strong><br />
			<?php echo $pirep->arrname?> (<?php echo $pirep->arricao; ?>)</td>

	</tr>
	
	<tr>
		<td valign="top">
		<h3>Flight Details</h3>							
		<strong>Flight Time: </strong> <?php echo $pirep->flighttime; ?><br />
		<strong>Date Submitted: </strong> <?php echo date(DATE_FORMAT, $pirep->submitdate);?><br />
		<?php
		if(!$fields)
		{
			//echo 'No additional data found';
		}
		else
		{
			foreach ($fields as $field)
			{
		?>		<strong><?php echo $field->title ?>:</strong> <?php echo $field->value ?><br />
		<?php
			}
		}
		?>
		
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
	<td>
		<h3>Additional Log Information:</h3>
	<?php
		# Simple, each line of the log ends with *
		# Just explode and loop.
		$log = explode('*', $pirep->log);
		foreach($log as $line)
		{
			echo $line .'<br />';
		}
		?>
	</tr>
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
		<td width="15%" nowrap><?php echo $comment->firstname . ' ' .$comment->lastname?></td>
		<td align="left"><?php echo $comment->comment?></td>
	</tr>
<?php
	}
	
	echo '</tbody></table>';
}
?>