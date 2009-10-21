
<h3>Flight <?php echo $pirep->code . $pirep->flightnum; ?></h3>

<table width="100%">
<tr>

<td width="50%">
<ul>
	<li><strong>Submitted By: </strong><a href="<?php echo SITE_URL.'/index.php/profile/view/'.$pirep->pilotid?>">
			<?php echo $pirep->firstname.' '.$pirep->lastname?></a></li>
	<li><strong>Departure Airport: </strong><?php echo $pirep->depname?> (<?php echo $pirep->depicao; ?>)</li>
	<li><strong>Arrival Airport: </strong><?php echo $pirep->arrname?> (<?php echo $pirep->arricao; ?>)</li>
	<li><strong>Aircraft: </strong><?php echo $pirep->aircraft . " ($pirep->registration)"?></li>
	<li><strong>Flight Time: </strong> <?php echo $pirep->flighttime; ?></li>
	<li><strong>Date Submitted: </strong> <?php echo date(DATE_FORMAT, $pirep->submitdate);?></li>
	<li><strong>Status: </strong>
		<?php

		if($pirep->accepted == PIREP_ACCEPTED)
			echo 'Accepted';
		elseif($pirep->accepted == PIREP_REJECTED)
			echo 'Rejected';
		elseif($pirep->accepted == PIREP_PENDING)
			echo 'Approval Pending';
		elseif($pirep->accepted == PIREP_INPROGRESS)
			echo 'Flight in Progress';
		?>
	</li>
</ul>
</td>
<td width="50%" valign="top" align="right">
<table class="balancesheet" cellpadding="0" cellspacing="0" width="100%">

	<tr class="balancesheet_header">
		<td align="" colspan="2">Flight Details</td>
	</tr>
	<tr>
		<td align="right">Gross Revenue: <br /> 
			(<?php echo $pirep->load;?> load / <?php echo FinanceData::FormatMoney($pirep->price);?> per unit  <br />
		<td align="right" valign="top"><?php echo FinanceData::FormatMoney($pirep->load * $pirep->price);?></td>
	</tr>
	<tr>
		<td align="right">Fuel Cost: <br />
			(<?php echo $pirep->fuelused;?> fuel used @ <?php echo $pirep->fuelunitcost?> / unit)<br />
		<td align="right" valign="top"><?php echo FinanceData::FormatMoney($pirep->fuelused * $pirep->fuelunitcost);?></td>
	</tr>
	</table>
</td>
</tr>
</table>

<?php
if($fields)
{
?>
<h3>Flight Details</h3>			
<ul>
	<?php
	foreach ($fields as $field)
	{
		if($field->value == '')
		{
			$field->value = '-';
		}
	?>		
		<li><strong><?php echo $field->title ?>: </strong><?php echo $field->value ?></li>
<?php
	}
	?>
</ul>	
<?php
}
?>

<?php
if($pirep->log != '')
{
?>
<h3>Additional Log Information:</h3>
<ul>
	<li><a href="#" onclick="$('#log').toggle(); return false;">View Log</a></li>
	<li id="log" style="display: none;">
		<div>
		<?php
		# Simple, each line of the log ends with *
		# Just explode and loop.
		$log = explode('*', $pirep->log);
		foreach($log as $line)
		{
			echo $line .'<br />';
		}
		?>
		</div>
	</li>
</ul>
<?php
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
	<td width="15%" nowrap><?php echo $comment->firstname . ' ' .$comment->lastname?></td>
	<td align="left"><?php echo $comment->comment?></td>
</tr>
<?php
}

echo '</tbody></table>';
}
?>