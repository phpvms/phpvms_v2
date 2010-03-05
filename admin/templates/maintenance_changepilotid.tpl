<p><b>NOTE:</b> Be VERY cautioned when changing a pilot ID. Even though this is 'controlled', problems can still occur. If you change the pilot ID to a number which is HIGHER than the highest pilot ID, all new registered pilots will have an ID starting after that. This WILL change their login ID. <b>This user cannot be logged in while doing the change.</b></p>
<form action="" method="post">

<b>Select Pilot:</b><br />
<select name="old_pilotid">
<option value="0">Select a Pilot</option>
<?php 
	foreach($allpilots as $pilot)
	{
		if(isset($_POST['old_pilotid']))
		{
			if($_POST['old_pilotid'] === $pilot->id)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
		}
		
		echo "<option value=\"{$pilot->pilotid}\" {$selected}>";
		echo PilotData::getPilotCode($pilot->code, $pilot->pilotid);
		echo ' - ' .$pilot->pilotid.' - '. $pilot->firstname.' '.$pilot->lastname;
		echo '</option>';		
	}
?>
</select>
<br /><br />
<b>Enter new ID - ONLY NUMERIC:</b><br />
<input type="text" name="new_pilotid" value="" />
<br />
<p><b>Are you sure?</b></p>
<input type="submit" name="submit" value="Confirm Change ID" />
</form>