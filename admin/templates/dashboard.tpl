<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');
MainController::Run('Dashboard', 'CheckForUpdates');
?>
<h3>Pilot Reports for the Past Week</h3>

<div id="reportcounts">Loading chart...</div>
<script type="text/javascript">
$(document).ready(function()
{
	$("#reportcounts").sparkline(<?php echo $reportcounts; ?>, {width: '90%', height: '150px'});

});
</script>

<table width="100%">
	<tr>
		<td valign="top">
			<h3>VA Stats:</h3>
			<strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?><br />
			<strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?><br />
			<strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?><br />
		</td>
		<td>
			<?php echo StatsData::AircraftFlownGraph();?>
		</td>
	</tr>
</table>