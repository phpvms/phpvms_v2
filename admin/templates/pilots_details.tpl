<form id="dialogform" action="<?php echo SITE_URL?>/admin/action.php/pilotadmin/viewpilots" method="post">
<table id="tabledlist" class="tablesorter" style="float: left">
<thead>
	<tr>
		<th colspan="2">Edit Pilot Details</th>	
	</tr>
</thead>
<tbody>

	<tr>
		<td>Avatar</td>
		<td>
			<?php
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
		</td>
	
	</tr>

	<tr>
		<td>First Name</td>
		<td><input type="text" name="firstname" value="<?php echo $pilotinfo->firstname;?>" /></td>
	</tr>

	<tr>
		<td>Last Name</td>
		<td><input type="text" name="lastname" value="<?php echo $pilotinfo->lastname;?>" /></td>
	</tr>
	<tr>
		<td>Email Address</td>
		<td><input type="text" name="email" value="<?php echo $pilotinfo->email;?>" /></td>
	</tr>
	<tr>
		<td>Airline</td>
		<td>
			<select name="code">
			<?php
			$allairlines = OperationsData::GetAllAirlines();
			foreach($allairlines as $airline)
			{
				if($pilotinfo->code == $airline->code)
					$sel =  ' selected';
				else
					$sel = '';
					
				echo '<option value="'.$airline->code.'" '
							.$sel.'>'.$airline->name.'</option>';
			}
			?>	
			</select>
		</td>
	</tr>
	<tr>
		<td>Location</td>
		<td><select name="location">
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
		</td>
	</tr>
	<tr>
		<td>Hub</td>
		<td>
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
		</td>
	</tr>
	<tr>
		<td>Current Rank</td>
		<td><?php echo $pilotinfo->rank;?></td>
	</tr>
	<tr>
		<td>Last Login</td>
		<td><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></td>
	</tr>
	<tr>
		<td>Total Flights</td>
		<td><input type="text" name="totalflights" value="<?php echo $pilotinfo->totalflights;?>" /></td>
	</tr>
	<tr>
		<td>Total Hours</td>
		<td><input type="text" name="totalhours" value="<?php echo $pilotinfo->totalhours;?>" /></td>
	</tr>
	<tr>
		<td>Total Pay</td>
		<td><input type="text" name="totalpay" value="<?php echo $pilotinfo->totalpay;?>" /></td>
	</tr>
<?php
if($customfields)
{
	foreach($customfields as $field)
	{
?>
	<tr>
		<td><?php echo $field->title;?></td>
		<td><input type="text" name="<?php echo $field->fieldname?>" 
							value="<?php echo $field->value?>" /></td>
	</tr>
<?php
	}
}
?>	
	<tr>
		<td colspan="2">
			<input type="hidden" name="pilotid" value="<?php echo $pilotinfo->pilotid;?>" />
			<input type="hidden" name="action" value="saveprofile" />
			<input type="submit" name="submit" value="Save Changes" />
			<div id="results"></div>
		</td>
	</tr>
</tbody>
</table>
</form>