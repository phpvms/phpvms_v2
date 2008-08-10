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
	$("#reportcounts").sparkline(<?=$reportcounts; ?>, {width: '90%', height: '150px'});

});
</script>