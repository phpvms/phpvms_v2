<h3>Reset Pilot Password</h3>
<p>If someone has forgetten their password and it needs to be reset</p>
<form id="dialogform" action="action.php?admin=viewpilots" method="post">

<dl>
	<dt>Enter new password</dt>
	<dd><input type="password" name="password1" /></dd>
	
	<dt>Enter password again</dt>
	<dd><input type="password" name="password2" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="userid" value="<?=Vars::GET('userid');?>" />
		<input type="hidden" name="action" value="changepassword" />
		<input type="submit" name="submit" value="Change Password" /></dd>
</dl>
</form>