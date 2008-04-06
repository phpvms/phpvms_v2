<div id="mainbox">
	<h3>Welcome <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?>!</h3>
	<div class="indent">
	<p>
		Your pilot code is <strong><?=$pilotcode;?></strong>.<br />
		Your latest flight was 
			<a href="?page=viewreport&pirepid=<?=$report->pirepid?>"><?=$report->code . $report->flightnum; ?></a><br />
		<br />
		<strong>Profile Options</strong><br /><br />
			<a href="?page=editprofile">Edit My Profile</a><br />
			<a href="?page=changepassword">Change my Password</a> <br />
		<br />
		<strong>Flight Operations</strong><br /><br />
			<a href="?page=viewpireps">View my PIREPs</a><br />
			<a href="?page=filepirep">File a Pilot Report</a><br />
			<a href="?page=schedules">View Flight Schedules</a> - Choose a flight
		</div>
	</p>
	<?php
		//TODO: Show PIREPS (call the function)
	?>
</div>
<div id="sidebar">
	<h3>Your Stats</h3>
	<p><strong><?=$userinfo->totalflights?></strong> total flights<br />
		<strong><?=$userinfo->totalhours?></strong> total hours</p>
</div>
<br />