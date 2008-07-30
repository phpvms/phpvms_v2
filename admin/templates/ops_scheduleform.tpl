<div id="wrapper">
<h3><?=$title?></h3>
<?php
//id="form"
?>
<form action="?admin=schedules" method="post">
<dl>

<dt>Code: </dt>
<dd>
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
	<p>Default is your airline callsign</dd>

<dt>Flight Number:</dt>
<dd><input type="text" name="flightnum" value="<?=$schedule->flightnum;?>" />
	<p><a href="http://en.wikipedia.org/wiki/Flight_number" target="_blank">Read about flight numbering (new window)</a></dd>

<dt>Leg:</dt>
<dd><input type="text" name="leg" value="<?=$schedule->leg;?>" />
	<p>Blank will default to "1"</p>
</dd>

<dt>Departure Airport:</dt>
<dd><select name="depicao">
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
</dd>
<dt>Arrival Airport: </dt>
<dd><select name="arricao">
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
</dd>

<dt></dt>
<dd><strong>Please include time zone (as PST, EST, etc)</strong></dd>
<dt>Departure Time: </dt>
<dd><input type="text" name="deptime" value="<?=$schedule->deptime?>" /></dd>

<dt>Arrival Time: </dt>
<dd><input type="text" name="arrtime" value="<?=$schedule->arrtime?>" /></dd>

<dt>Distance: </dt>
<dd><input type="text" name="distance" value="<?=$schedule->distance?>" /></dd>

<dt>Flight Time: </dt>
<dd><input type="text" name="flighttime" value="<?=$schedule->flighttime?>" /></dd>

<dt>Equipment: </dt>
<dd><select name="aircraft">
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
</dd>

<dt>Route (optional)</dt>
<dd><textarea name="route"><?=$schedule->route?></textarea></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="id" value="<?=$schedule->id?>" />
	<input type="submit" name="submit" value="<?=$title?>" /> <input type="submit" class="jqmClose" value="Close" />
</dd>
</dl>
</form>
</div>