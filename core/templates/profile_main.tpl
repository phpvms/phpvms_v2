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
			<a href="?page=editprofile">Edit Profile</a> - Edit your profile, including changing your password <br />
			<a href="?page=schedules">View Flight Schedules</a> - Choose a flight
		</p>
		<?php
			//TODO: Show PIREPS (call the function)
		?>
	</dd>
</dl>