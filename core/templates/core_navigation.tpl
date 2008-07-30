<li><a href="<?=SITE_URL ?>/index.php/Frontpage">home</a></li>
<?php
if(!Auth::LoggedIn())
{
	// Show these if they haven't logged in yet
?>
	<li><a href="<?=SITE_URL ?>/index.php/Login/">Login</a></li>
	<li><a href="<?=SITE_URL ?>/index.php/Login/register">Register</a></li>
<?php
}
else
{
	// Show these items only if they are logged in
?>
	<li><a href="<?=SITE_URL ?>/index.php/PilotProfile">Pilot Center</a></li>
	
<?php
}
?>
<li><a href="<?=SITE_URL ?>/index.php/Pilots">Pilots</a></li>
<?=$MODULE_NAV_INC;?>
<?php//<li><a href="?page=acars">Live Map</a></li>
?>
<?php
if(Auth::LoggedIn())
{
	if(Auth::UserInGroup('Administrators'))
	{
		echo '<li><a href="'.SITE_URL.'/admin">Admin Center</a></li>';
	}
?>


<li><a href="index.php/Login/logout">Log Out</a></li>
<?php
}
?>