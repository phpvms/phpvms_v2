<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');

echo $updateinfo;
?>
<h3>Pilot Reports for the Past Week</h3>
<div id="reportcounts" align="center" width="400px" >
<?php
# Create the chart
//$reportcounts = '';
if(!$reportcounts)
{
	$reportcounts = array();
}
$graph = new ChartGraph('pchart', 'line', 680, 180);
$graph->setFontSize(8);
$graph->AddData($reportcounts, array_keys($reportcounts));
$graph->setTitles('PIREPS Filed');
$graph->GenerateGraph();

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