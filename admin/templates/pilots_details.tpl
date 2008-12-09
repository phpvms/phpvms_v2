<form id="dialogform" action="action.php?admin=viewpilots" method="post">
<dl> 
	<dt>Avatar</dt>
		<dd><?php
		$pilotcode = PilotData::GetPilotCode($pilotinfo->code, $pilotinfo->pilotid);
			
		if(!file_exists(SITE_ROOT.AVATAR_PATH.'/'.$pilotcode.'.png'))
		{
			echo 'None selected';
		}
		else
		{
		?>
			<img src="<?php	echo SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png';?>" />
		<?php
		}
		?>
	</dd>
	
	<dt>First Name</dt>
	<dd><input type="text" name="firstname" value="<?php echo $pilotinfo->firstname;?>" /></dd>
	
	<dt>Last Name</dt>
	<dd><input type="text" name="lastname" value="<?php echo $pilotinfo->lastname;?>" /></dd>
	
	
	<dt>Email Address</dt>
	<dd><input type="text" name="email" value="<?php echo $pilotinfo->email;?>" /></dd>

	<dt>Airline</dt>
	
	
	<dd>
	<select name="code">
	<?php
	$allairlines = OperationsData::GetAllAirlines();
	foreach($allairlines as $airline)
	{
		if($pilotinfo->code == $airline->code)
			$sel =  ' selected';
		else
			$sel = '';
			
		echo '<option value="'.$airline->code.'" '.$sel.'>'.$airline->name.'</option>';
	}
	?>	
	</select>
	</dd>
	
	<dt>Location</dt>
	<dd><select name="location">
			<?php
			foreach($countries as $countryCode=>$countryName)
			{
				if($pilotinfo->location == $countryCode)
					$sel = 'selected="selected"';
				else	
					$sel = '';
				
				echo '<option value="'.$countryCode.'" '.$sel.'>'.$countryName.'</option>';
			}
					?>
		</select>
	</dd>
	
	<dt>Hub</dt>
	<dd>
	<select name="hub">
	<?php
	$allhubs = OperationsData::GetAllHubs();
	foreach($allhubs as $hub)
	{
		if($pilotinfo->hub == $hub->icao)
			$sel = ' selected';
		else
			$sel = '';
		
		echo '<option value="'.$hub->icao.'" '.$sel.'>'.$hub->icao.' - ' . $hub->name .'</option>';
	}
	?>	
	</select>
	</dd>
	
	<dt>Current Rank</dt>
	<dd><?php echo $pilotinfo->rank;?></dd>

	<dt>Last Login</dt>
	<dd><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></dd>

	<dt>Total Flights</dt>
	<dd><input type="text" name="totalflights" value="<?php echo $pilotinfo->totalflights;?>" /></dd>

	<dt>Total Hours</dt>
	<dd><input type="text" name="totalhours" value="<?php echo $pilotinfo->totalhours;?>" /></dd>
	
	<dt>Total Pay</dt>
	<dd><input type="text" name="totalpay" value="<?php echo $pilotinfo->totalpay;?>" /></dd>
	
<?php
if($customfields)
{
	foreach($customfields as $field)
	{
?>
	<dt><?php echo $field->title;?></dt>
	<dd><input type="text" name="<?php echo $field->fieldname?>" value="<?php echo $field->value?>" /></dd>
<?php
	}
}
?>	
	<dt></dt>
	<dd>
		<input type="hidden" name="pilotid" value="<?php echo $pilotinfo->pilotid;?>" />
		<input type="hidden" name="action" value="saveprofile" />
		<input type="submit" name="submit" value="Save Changes" />
		<div id="results"></div>
	</dd>
</dl>
</form>