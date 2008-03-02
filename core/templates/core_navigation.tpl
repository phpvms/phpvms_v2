<?php
if(!Auth::LoggedIn())
{
	// Show these if they haven't logged in yet
?>
	<li><a href="?page=login">Login</a></li>
	<li><a href="?page=register">Register</a></li>
<?php
}
else
{
	// Show these items only if they are logged in
?>
	<li><a href="?page=profile">Profile</a></li>
	<li><a href="#">PIREPs</a>
		<ul>
			<li><a href="?page=filepirep">File a PIREP</a></li>
			<li><a href="?page=viewpireps">View PIREPs</a></li>
		</ul>
	</li>
<?php
}
?>
<li><a href="?page=acars">Live ACARS Map</a></li>
<li><a href="?page=contact">Contact Us</a></li>