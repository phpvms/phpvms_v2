<h1>Login</h1>
<form name="loginform" action="<?php echo SITE_URL?>/index.php/Login" method="post">
<?php echo "<?xml version='1.0'?>"; ?>
<?php
if($message)
	echo '<p class="error">'.$message.'</p>';
?>
<dl>
	<dt>E-mail Address:</dt>
	<dd><input type="text" name="email" value="" />
	
	<dt>Password:</dt>
	<dd><input type="password" name="password" value="" />
	
	<dt></dt>
	<dd><input type="hidden" name="redir" value="index.php/profile" />
		<input type="hidden" name="action" value="login" />
		<input type="submit" name="submit" value="Log In" />
		
	<dt></dt>
	<dd><a href="<?php echo SITE_URL ?>/index.php/Login/forgotpassword">I forgot my password</a></dd>
</dl>
</form>