<h3>Pilot Profile</h3>

<p>Welcome <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?>!</p>
<br />
<p><a href="?page=editprofile">Edit Profile</a> - Edit your profile, including changing your password</p>
<p><a href="?page=schedules">View Flight Schedules</a> - Choose a flight</p>
<?php
	//TODO: Show PIREPS (call the function)
?>