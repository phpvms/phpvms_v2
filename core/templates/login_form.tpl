<h1>Login</h1>
<form name="loginform" action="?page=login" method="post">
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
	<dd><input type="hidden" name="redir" value="<?=$redir;?>" />
		<input type="hidden" name="action" value="login" />
		<input type="submit" name="submit" value="Log In" />
		
	<dt></dt>
	<dd><a href="?page=forgotpassword">I forgot my password</a></dd>
</dl>
</form>