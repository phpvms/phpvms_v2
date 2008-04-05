<h3>Welcome <?=Auth::$userinfo->firstname .' '. Auth::$userinfo->lastname;?></h3>
<?php
MainController::Run('Dashboard', 'CheckForUpdates');

echo '<p><strong>Pilot Reports for the Past Week</strong></p>';
MainController::Run('Dashboard', 'ShowReportCounts');
?>