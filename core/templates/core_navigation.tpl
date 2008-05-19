<li><a href="?page=">home</a></li>
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
	<li><a href="?page=profile">Pilot Center</a></li>
	
<?php
}
?>
<li><a href="?page=pilots">Pilots</a></li>
<?=$MODULE_NAV_INC;?>
<?php//<li><a href="?page=acars">Live Map</a></li>
?>
<li><a href="?page=contact">Contact Us</a></li>
<?php
if(Auth::LoggedIn())
{
	if(Auth::UserInGroup('Administrators'))
	{
		echo '<li><a href="'.SITE_URL.'/admin">Admin Center</a></li>';
	}
?>


<li><a href="?page=logout">Log Out</a></li>
<?php
}
?>