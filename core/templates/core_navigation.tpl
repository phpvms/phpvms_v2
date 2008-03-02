<?php
if(!Admin::LoggedIn())
{
?>
	<li><a href="?page=login">Login</a></li>
	<li><a href="?page=register">Register</a></li>
<?php
}
else
{
?>
	<li><a href="?page=profile">Profile</a></li>
<?php
}
?>
<li><a href="?page=contact">Contact Us</a></li>