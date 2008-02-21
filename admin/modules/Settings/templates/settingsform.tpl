
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
			
			switch($setting->name)
			{
			
				case 'PHPVMS_VERSION':
					echo '<dd>'.$setting->value.'</dd>';
					break;
					
				case 'CURRENT_SKIN':
				
					$skins = Util::GetAvailableSkins();
   
					echo '<dd>
						<SELECT name="CURRENT_SKIN">';
   
						$tot = count($skins);
						for($i=0;$i<$tot;$i++)
						{
							$sel = (CURRENT_SKIN == $skins[$i])? 'selected' : '';
   
							echo '<option value="'.$skins[$i].'" '. $sel . '>'.$skins[$i].'</option>';
						}
   
					echo '</SELECT>
						  <p>'.$setting->descrip.'</p>
						  </dd>';
					break;
					
				default:
				
					echo '<dd>';
					
					if($setting->value == 'true' || $setting->value == 'false')
					{
						if($setting->value == 'true')
						{
							$sel_true = 'selected';
							$sel_false = '';
						}
						else 
						{
							$sel_true = '';
							$sel_false = 'selected';
						}
   
						echo '
								<SELECT name="' . $setting->name . '" onChange="showChanged();" >
								<option value="true" '. $sel_true . '>Enabled</option>
								<option value="false" ' . $sel_false . '>Disabled</option>
							  </SELECT>';
					}
					else 
					{
						echo '<input type="text" name="'.$setting->name.'" value="'.$setting->value.'" />';
					}
					
					echo '<p>'.$setting->descrip.'</p>
						</dd>';
					break;
			}
		}
	}
?>
	<dt></dt>
	<dd><input type="hidden" name="action" value="savesettings">
		<input type="submit" name="submit" value="Save Settings" />
	</dd>
	
	
</dl>
</form>