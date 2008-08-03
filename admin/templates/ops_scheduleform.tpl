<div id="wrapper">
<h3><?=$title?></h3>
<?php
//id="form"
?>
<form action="?admin=schedules" method="post">
<table width="100%">
<tr>
	<td>Code: </td>
	<td>
		<select name="code">
		<?php
		
		foreach($allairlines as $airline)
		{
			if($airline->code == $schedule->code)
				$sel = 'selected';
			else
				$sel = '';
	
			echo '<option value="'.$airline->code.'" '.$sel.'>'.$airline->name.'</option>';
		}
		
		?>
		</select>
		<p>Default is your airline callsign
	</td>
</tr>
<tr>
	<td>Flight Number:</td>
	<td>
		<input type="text" name="flightnum" value="<?=$schedule->flightnum;?>" />
	</td>
</tr>
<tr>
	<td valign="top">Leg:</td>
	<td><input type="text" name="leg" value="<?=$schedule->leg;?>" />
		<p>Blank will default to "1"</p>
	</td>
</tr>
<tr>
	<td width="3%" nowrap>Departure Airport:</td>
	<td><select name="depicao">
		<?php
		foreach($allairports as $airport)
		{
			if($airport->icao == $schedule->depicao)
			{
				$sel = 'selected';
			}
			else
			{
				$sel = '';
			}
	
			echo '<option value="'.$airport->icao.'" '.$sel.'>'.$airport->icao.' ('.$airport->name.')</option>';
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td>Arrival Airport: </td>
	<td><select name="arricao">
		<?php
		foreach($allairports as $airport)
		{
	        if($airport->icao == $schedule->arricao)
			{
				$sel = 'selected';
			}
			else
			{
				$sel = '';
			}
	
			echo '<option value="'.$airport->icao.'" '.$sel.'>'.$airport->icao.' ('.$airport->name.')</option>';
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td valign="top">Departure Time: </td>
	<td><input type="text" name="deptime" value="<?=$schedule->deptime?>" />
		<p>Time can be entered in any format</p>
	</td>
</tr>
<tr>
	<td valign="top">Arrival Time: </td>
	<td><input type="text" name="arrtime" value="<?=$schedule->arrtime?>" />
		<p>Time can be entered in any format</p>
	</td>
</tr>
<tr>
	<td>Distance: </td>
	<td><input type="text" name="distance" value="<?=$schedule->distance?>" /></td>
</tr>
<tr>
	<td>Flight Time: </td>
	<td><input type="text" name="flighttime" value="<?=$schedule->flighttime?>" /></td>
</tr>
<tr>
	<td>Equipment: </td>
	<td><select name="aircraft">
		<?php
		foreach($allaircraft as $aircraft)
		{
			if($aircraft->name == $schedule->aircraft)
				$sel = 'selected';
			else
				$sel = '';
	
			echo '<option value="'.$aircraft->name.'" '.$sel.'>'.$aircraft->name.' ('.$aircraft->icao.')</option>';
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td valign="top">Route (optional)</td>
	<td><textarea name="route" style="width: 60%; height: 150px"><?=$schedule->route?></textarea>
	</td>
</tr>
<tr>
	<td></td>
	<td><input type="hidden" name="action" value="<?=$action?>" />
		<input type="hidden" name="id" value="<?=$schedule->id?>" />
		<input type="submit" name="submit" value="<?=$title?>" /> <input type="submit" class="jqmClose" value="Close" />
	</td>
</tr>
</table>
</form>
</div>