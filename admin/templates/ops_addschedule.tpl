<h3>Add Schedule</h3>
<form id="form" action="action.php?admin=schedules" method="post">
<dl>

<dt>Code: </dt>
<dd><input type="text" name="code" value="<?=PID_PREFIX;?>" /></dd>

<dt>Flight Number:</dt>
<dd><input type="text" name="flightnum" value="" /></dd>

<dt>Leg:</dt>
<dd><input type="text" name="leg" value="" />
	<p>Blank will default to "1"</p>
</dd>

<dt>Departure Airport:</dt>
<dd><select name="depicao">
	<?php
		echo $airports;
	?>
	</select>  
</dd>
<dt>Arrival Airport: </dt>
<dd><select name="arricao">
	<?php
		echo $airports;
	?>
	</select>
</dd>
<dt>Departure Time: </dt>
<dd><input type="text" name="deptime" value="" /></dd>

<dt>Arrival Time: </dt>
<dd><input type="text" name="arrtime" value="" /></dd>

<dt>Flight Time: </dt>
<dd><input type="text" name="flighttime" value="" /></dd>

<dt>Equipment: </dt>
<dd><select name="aircraft">
	<?php echo $aircraft; ?>
	</select>
</dd>

<dt>Route (optional)</dt>
<dd><textarea name="route"></textarea></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="addschedule" />
	<input type="submit" name="submit" value="Add Schedule" />
</dd>
</dl>
</form>