
<h1>Site Settings</h1>
<p>Select available site options from this page. Don't forget to save!</p>
<form  id="settingsform" method="post" action="?admin=settings">
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
		
			echo '<dt><strong>'.$setting->name . '</strong></dt>';
			
			if($setting->name == 'PHPVMS_VERSION')
				echo '<dd>'.$setting->value.'</dd>';
			else
				echo '<dd><input type="text" name="'.$setting->name.'" value="'.$setting->value.'" />
				<p>'.$setting->descrip.'</p></dd>';
		}
	}
?>
	<dt></dt>
	<dd><input type="submit" name="saveSettings" id="saveSettings" value="Save Settings" /></dd>
</dl>
</form>