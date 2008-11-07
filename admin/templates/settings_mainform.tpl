<h3>Site Settings</h3>
<p>Select available site options from this page. Don't forget to save!</p>
<form id="form" method="post" action="action.php?admin=settings">

<table id="tabledlist" class="tablesorter">
<thead>
	<tr>
		<th>Setting Name</th>
		<th>Setting Value</th>
	</tr>
</thead>
<tbody>
<?php
	if(!$allsettings)
	{
		echo '<p>No settings have been added</p>';
	}
	else
	{
		foreach($allsettings as $setting)
		{
			
			echo '<tr>
					<td width="15%" nowrap><strong>'.$setting->friendlyname . '</strong></td>';
			
			switch($setting->name)
			{
			
				case 'PHPVMS_VERSION':
					echo '<td>'.PHPVMS_VERSION.'</td>';
					break;
					
				case 'CURRENT_SKIN':
				
					$skins = SiteData::GetAvailableSkins();
   
					echo '<td>
						<SELECT name="CURRENT_SKIN">';
						
						$tot = count($skins);
						for($i=0;$i<$tot;$i++)
						{
							$sel = (CURRENT_SKIN == $skins[$i])? 'selected' : '';
   
							echo '<option value="'.$skins[$i].'" '. $sel . '>'.$skins[$i].'</option>';
						}
   
					echo '</SELECT>
						  
						  </td>';
						 //<p>'.$setting->descrip.'</p>
					break;
					
				default:
				
					echo '<td>';
					
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
						</td>';
					break;
			}
			
			echo '</tr>';
		}
	}
?>
<tr>
	<td></td>
	<td><input type="hidden" name="action" value="savesettings">
		<input type="submit" name="submit" value="Save Settings" />
	</td>
</tr>
</tbody>
</table>
</form>