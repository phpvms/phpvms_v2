<?php
$pilotid = Vars::GET('pilotid');
?>
<h3>Reset Pilot Password</h3>
<p>If someone has forgetten their password and it needs to be reset. </p>
<form id="pilotoptionchangepass" action="<?php echo SITE_URL?>/admin/action.php/pilotadmin/viewpilots" method="post">
<dl>
	<dt>Enter new password</dt>
	<dd><input type="password" name="password1" /></dd>
	
	<dt>Enter password again</dt>
	<dd><input type="password" name="password2" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="pilotid" value="<?php echo $pilotid;?>" />
		<input type="hidden" name="action" value="changepassword" />
		<input type="submit" name="submit" value="Change Password" /></dd>
</dl>
</form>
<?php
if($pilotid != Auth::$userinfo->pilotid)
{?>
<h3>Delete Pilot</h3>
<form id="deletepilot" action="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots" method="post">
<dl>	
	<dt></dt>
	<dd><input type="hidden" name="pilotid" value="<?php echo $pilotid;?>" />
		<input type="hidden" name="action" value="deletepilot" />
		<input type="submit" name="submit" value="Delete Pilot" /></dd>
</dl>
</form>
<?php
}
?>