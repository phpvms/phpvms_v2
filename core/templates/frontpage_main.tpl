<div id="mainbox">
<?php

	// Show the News module, call the function ShowNewsFront
	//	This is in the modules/Frontpage folder
	
	News::ShowNewsFront();
?>
</div>
<div id="sidebar">
	<h3>Recent Reports</h3>
	
	<?php MainController::Run('PIREPS', 'RecentFrontPage', 1); ?>

	<h3>Newest Pilots</h3>
	<p>List 'em here</p>
</div>