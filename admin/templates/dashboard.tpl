<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');

echo $updateinfo;
?>
<h3>Pilot Reports for the Past Week</h3>
<div id="reportcounts" align="center" width="400px" >
	<?php

	/*$chart = new ChartGraph('pchart', 'line', 500, 150);
	$chart->setTitles('Total PIREPS');
	$chart->AddData($reportcounts, $reportcounts);
	echo '<img src="'.$chart->GenerateGraph().'" />'; */

	# Create the chart
	$chart = new googleChart(implode(',',$reportcounts), 'line', '', '500x150');
	$chart->setLabels(implode('|', $reportcounts), 'bottom');
	echo '<img src="'.$chart->draw(false).'" />';
	
	?>
</div>
<table width="100%">
	<tr>
		<td valign="top" width="50%">
			<h3>VA Stats:</h3>
			<ul>
				<li><strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?></li>
				<li><strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?></li>
				<li><strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?></li>
			</ul>
		</td>
		<td valign="top" width="50%">
			<h3 style="margin-bottom: 0px;">Latest News</h3>
			<?php echo $latestnews; ?>
		</td>
	</tr>
</table>
<?php
/*
<script type="text/javascript">
$(document).ready(function()
{
	$("#reportcounts").sparkline([<?php echo implode(',', $reportcounts); ?>], {width: '400px', height: '100px', type: 'bar'});

});
</script>
*/
?>