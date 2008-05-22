<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');
MainController::Run('Dashboard', 'CheckForUpdates');

echo '<p><strong>Pilot Reports for the Past Week</strong></p>';
StatsData::ShowReportCounts();
?>