<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');

echo $updateinfo;
?>
<h3>Pilot Reports for the Past Week</h3>
<div id="reportcounts" align="center">Loading chart...</div>
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
			<h3>Latest News</h3>
			<?php echo $latestnews; //StatsData::AircraftFlownGraph();?>
		</td>
	</tr>
</table>
<script type="text/javascript">
$(document).ready(function()
{
	$("#reportcounts").sparkline(<?php echo $reportcounts; ?>, {width: '90%', height: '100px'});

});
</script>