<h3>Pilot Profile</h3>
<dl>
	<dt>Welcome <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?>!</dt>
	<dd>You currently have:
		<p><strong><?=$userinfo->totalflights?></strong> total flights<br />
			<strong><?=$userinfo->totalhours?></strong> total hours</p>
	</dd>
	<dt>Options</dt>
	<dd>
		<p>
			<strong>Profile</strong><br />
			<a href="?page=editprofile">Edit My Profile</a><br />
			<a href="?page=changepassword">Change my Password</a> <br />
			<br />
			<strong>Flight Operations</strong><br />
			<a href="?page=viewpireps">View my PIREPs</a><br />
			<a href="?page=filepirep">File a Pilot Report</a><br />
			<a href="?page=schedules">View Flight Schedules</a> - Choose a flight
		</p>
		<?php
			//TODO: Show PIREPS (call the function)
		?>
	</dd>
</dl>