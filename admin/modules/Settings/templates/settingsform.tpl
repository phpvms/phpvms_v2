
<h1>Site Settings</h1>
<p>Select available site options from this page. Don't forget to save!</p>
<form id="form" method="post" action="action.php?admin=settings">
<dl>
<?php
	if(!$allsettings)
	{
		echo '<p>No settings have been added</p>';
	}
	else
	{
		foreach($allsettings as $setting)
		{
			echo '<dt><strong>'.$setting->friendlyname . '</strong></dt>';
			
			if($setting->name == 'PHPVMS_VERSION')
				echo '<dd>'.$setting->value.'</dd>';
			else
				echo '<dd><input type="text" name="'.$setting->name.'" value="'.$setting->value.'" />
						<p>'.$setting->descrip.'</p></dd>';
		}
	}
?>
	<dt></dt>
	<dd><input type="hidden" name="action" value="savesettings">
		<input type="submit" name="submit" value="Save Settings" />
	</dd>
	
	
</dl>
</form>