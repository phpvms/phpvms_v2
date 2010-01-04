<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');

echo $updateinfo;
?>
<h3>Pilot Reports for the Past Week</h3>
<div align="center" style="width=98%">
	<div id="reportcounts" align="center" width="400px" >
	<img src="<?php echo SITE_URL?>/lib/images/loading.gif" /><br /><br />
	Loading...
	</div>
</div>
<table width="100%">
	<tr>
		<td valign="top">		
			<h3>VA Stats:</h3>
			<ul>
				<li><strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?></li>
				<li><strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?></li>
				<li><strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?></li>
			</ul>
		</td>
		<?php
		if(Config::Get('VACENTRAL_ENABLED') == true)
		{
		?>
		<td valign="top" width="50%">
			
				<h3>vaCentral Status: </h3>
				<p>You have <strong><?php echo $unexported_count?></strong> PIREPS waiting for export to vaCentral. 
				<a href="<?php echo SITE_URL ?>/admin/index.php/vacentral/sendqueuedpireps">Click here to send them</a> </p>
			
		</td>
		<?php
		}
		?>
	</tr>
	<tr>
		<td valign="top">
			<h3 style="margin-bottom: 0px;">Latest News</h3>
			<?php echo $phpvms_news; ?>
			<p><a href="http://www.phpvms.net" target="_new">View All News</a></p>
		</td>
		<?php
		if(Config::Get('VACENTRAL_ENABLED') == true)
		{
		?>
		<td valign="top" valign="50%">
			
			<h3 style="margin-bottom: 0px;">Latest vaCentral News</h3>
			<?php echo $vacentral_news; ?>
			<p><a href="http://www.vacentral.net" target="_new">View All News</a></p>
		</td>
		<?php
		}
		?>
	</tr>
</table>
<?php
/*
	Added in 2.0!
*/
$chart_width = '800';
$chart_height = '200';

/* Don't need to change anything below this here */
?>
<script type="text/javascript" src="<?php echo fileurl('/lib/js/ofc/js/swfobject.js')?>"></script>
<script type="text/javascript">
swfobject.embedSWF("<?php echo fileurl('/lib/js/ofc/open-flash-chart.swf');?>", 
	"reportcounts", "<?php echo $chart_width;?>", "<?php echo $chart_height;?>", 
	"9.0.0", "expressInstall.swf", 
	{"data-file":"<?php echo SITE_URL;?>/admin/action.php/dashboard/pirepcounts"});
</script>
