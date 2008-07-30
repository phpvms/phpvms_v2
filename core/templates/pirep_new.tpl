<h3>File a Flight Report</h3>

<?php
if($message!='')
	echo '<div id="error">'.$message.'</div>';
?>
<form action="<?=SITE_URL?>/index.php/pireps/mine" method="post">
<dl>
	<dt>Pilot:</dt>
	<dd><strong><?=$pilot;?></strong> (<?=$pilotcode ?>)</dd>
	
	<dt>Select Airline:</dt>
	<dd>
		<select name="code" id="code">
			<option value="">Select your airline</option>
		<?php
		foreach($allairlines as $airline)
		{
			echo '<option value="'.$airline->code.'">'.$airline->name.'</option>';
		}
		?>
		</select>
	</dd>
	
	<dt>Enter Flight Number:</dt>
	<dd><input type="text" name="flightnum" /></dd>
	
	<dt>Select Departure Airport:</dt>
	<dd>
		<div id="depairport">
		<select id="depicao" name="depicao">
			<option value="">Select a departure airport</option>
			<?php
			foreach($allairports as $airport)
			{
				echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
			}
			?>
		</select>
		</div>
	</dd>
	
	<dt>Select Arrival Airport:</dt>
	<dd>
		<div id="arrairport">
		<select id="arricao" name="depicao">
			<option value="">Select an arrival airport</option>
			<?php
			foreach($allairports as $airport)
			{
				echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
			}
			?>
		</select>
		</div>
	</dd>
	
	<dt>Select Aircraft:</dt>
	<dd>
		<select name="aircraft" id="aircraft">
			<option value="">Select the aircraft of this flight</option>
		<?php
		foreach($allaircraft as $aircraft)
		{
			echo '<option value="'.$aircraft->name.'">'.$aircraft->name.'</option>';
		}
		?>
		</select>
	</dd>
	
	<dt>Flight Time</dt>
	<dd><input type="text" name="flighttime" />
		<p>Enter as hours - "5.5" is five and a half hours</p></dd>
		
	<dt>Comment</dt>
	<dd><textarea name="comment" style="width: 100%"></textarea></dd>
	
	<dt></dt>
	<dd><input type="submit" name="submit_pirep" value="File Flight Report" /></dd>
</dl>

</form>