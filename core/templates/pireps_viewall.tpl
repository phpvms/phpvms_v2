<h3>PIREPs List</h3>
<p><?=$descrip;?></p>
<?php
if(!$pireps)
{
	echo '<p>No reports have been found</p>';
	return;
}	
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>	
	<th>Arrival</th>
	<th>Aircraft</th>
	<th>Flight Time</th>
	<th>Submitted</th>
	<th>Status</th>
</tr>
</thead>
<tbody>
<?php
foreach($pireps as $report)
{
?>
<tr>
	<td align="center">
		<a href="?page=viewreport&pirepid=<?=$report->pirepid?>"><?=$report->code . $report->flightnum; ?></a>
	</td>
	<td align="center"><?=$report->depicao; ?></td>
	<td align="center"><?=$report->arricao; ?></td>
	<td align="center"><?=$report->aircraft; ?></td>
	<td align="center"><?=$report->flighttime; ?></td>
	<td align="center"><?=date(DATE_FORMAT, $report->submitdate); ?>
		
	</td>
	<td align="center">
		<?php
		
		if($report->accepted == PIREP_ACCEPTED)
			echo '<div id="success">Accepted</div>';
		elseif($report->accepted == PIREP_REJECTED)
			echo '<div id="error">Rejected</div>';
		elseif($report->accepted == PIREP_PENDING)
			echo '<div id="error">Approval Pending</div>';
		elseif($report->accepted == PIREP_INPROGRESS)
			echo '<div id="error">Flight in Progress</div>';
		
		?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>