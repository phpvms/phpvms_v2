<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');

echo $updateinfo;
?>
<h3>Pilot Reports for the Past Week</h3>
<div id="reportcounts" align="center" width="400px" >
<img src="<?php echo SITE_URL?>/lib/images/loading.gif" /><br /><br />
Loading...
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
			
			<?php
			if(Config::Get('VACENTRAL_ENABLED') == true)
			{
			?>
			<h3>vaCentral Status: </h3>
				<p>You have <strong><?php echo $unexported_count?></strong> PIREPS waiting for export to vaCentral. 
				<a href="<?php echo SITE_URL ?>/admin/index.php/vacentral/sendqueuedpireps">Click here to send them</a> </p>
			
			<?php
			}
			?>
		</td>
		<td valign="top" width="50%">
			<h3 style="margin-bottom: 0px;">Latest News</h3>
			<?php echo $latestnews; ?>
		</td>
	</tr>
</table>
<script type="text/javascript">
$(document).ready(function()
{
	$("#reportcounts").load("<?php echo SITE_URL;?>/admin/action.php/dashboard/pirepcounts");
});
</script>