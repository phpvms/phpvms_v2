<div id="mainbox">
	<h3>Pilot Center</h3>
	<div class="indent">
	<p>
	Welcome back <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?> (<strong><?=$pilotcode;?></strong>)!
	<br />
	Your latest flight was <a href="<?=SITE_URL?>/index.php/pireps/view/<?=$report->pirepid?>"><?=$report->code . $report->flightnum; ?></a>

	<?php
	if($nextrank)
	{
	?>
		<br />
		You have <?=($nextrank->minhours-$userinfo->totalhours)?>
		hours left until your promotion to <?=$nextrank->rank?>
	<?php
	}
	?>
	</p>
	<p>
		<strong>Profile Options</strong><br /><br />
			<a href="<?=SITE_URL ?>/index.php/profile/editprofile">Edit My Profile</a><br />
			<a href="<?=SITE_URL ?>/index.php/profile/changepassword">Change my Password</a> <br />
		<br />
		<strong>Flight Operations</strong><br /><br />
			<a href="<?=SITE_URL?>/index.php/pireps/mine">View my PIREPs</a><br />
			<a href="<?=SITE_URL?>/index.php/pireps/routesmap">View a map of all my flights</a><br />
			<a href="<?=SITE_URL?>/index.php/pireps/filepirep">File a Pilot Report</a><br />
			<a href="<?=SITE_URL?>/index.php/Schedules/view">View Flight Schedules</a><br />
			<a href="<?=SITE_URL?>/index.php/Schedules/bids">View my flight bids</a>
	</p>
	</div>
</div>
<div id="sidebar">
	<h3>Your Stats</h3>
	<p> <strong>Rank: </strong><?=$userinfo->rank;?> <br />
		<strong>Total Flights: </strong><?=$userinfo->totalflights?><br />
		<strong>Total Hours: </strong><?=$userinfo->totalhours?></p>

	<strong>Aircraft Flown</strong>
	<p><?php StatsData::PilotAircraftFlownGraph($userinfo->pilotid); ?></p>
</div>
<br />