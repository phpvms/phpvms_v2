<li><a href="<?=SITE_URL ?>/index.php/Frontpage">home</a></li>
<?php
if(!Auth::LoggedIn())
{
	// Show these if they haven't logged in yet
?>
	<li><a href="<?=SITE_URL ?>/index.php/login/">Login</a></li>
	<li><a href="<?=SITE_URL ?>/index.php/registration">Register</a></li>
<?php
}
else
{
	// Show these items only if they are logged in
?>
	<li><a href="<?=SITE_URL ?>/index.php/profile">Pilot Center</a></li>
	
<?php
}
?>
<li><a href="<?=SITE_URL ?>/index.php/pilots">Pilots</a></li>
<li><a href="<?=SITE_URL ?>/index.php/acars">Live Map</a></li>
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


<li><a href="<?=SITE_URL ?>/index.php/login/logout">Log Out</a></li>
<?php
}
?>