<h3>File a Flight Report</h3>
<?php
if($message!='')
	echo '<div id="error">'.$message.'</div>';
?>
<form action="<?=SITE_URL?>/index.php/pireps/mine" method="post">
<dl>
	<dt>Pilot:</dt>
	<dd><strong><?=Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname;?></strong></dd>
	
	<dt>Select Airline:</dt>
	<dd>
		<select name="code" id="code">
			<option value="">Select your airline</option>
		<?php
		foreach($allairlines as $airline)
		{
			$sel = ($_POST['code'] == $airline->code || $bid->code == $airline->code)?'selected':'';
				
			echo '<option value="'.$airline->code.'" '.$sel.'>'.$airline->name.'</option>';
		}
		?>
		</select>
	</dd>
	
	<dt>Enter Flight Number:</dt>
	<dd><input type="text" name="flightnum" value="<?=$bid->flightnum?>" /></dd>
	
	<dt>Select Departure Airport:</dt>
	<dd>
		<div id="depairport">
		<select id="depicao" name="depicao">
			<option value="">Select a departure airport</option>
			<?php
			foreach($allairports as $airport)
			{
				$sel = ($_POST['depairport'] == $airport->icao || $bid->depicao == $airport->icao)?'selected':'';
				
				echo '<option value="'.$airport->icao.'" '.$sel.'>'.$airport->icao . ' - '.$airport->name .'</option>';
			}
			?>
		</select>
		</div>
	</dd>
	
	<dt>Select Arrival Airport:</dt>
	<dd>
		<div id="arrairport">
		<select id="arricao" name="arricao">
			<option value="">Select an arrival airport</option>
			<?php
			foreach($allairports as $airport)
			{
				$sel = ($_POST['arricao'] == $airport->icao || $bid->arricao == $airport->icao)?'selected':'';
				
				echo '<option value="'.$airport->icao.'" '.$sel.'>'.$airport->icao . ' - '.$airport->name .'</option>';
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
			$sel = ($_POST['aircraft'] == $aircraft->name)?'selected':'';
			
			echo '<option value="'.$aircraft->name.'" '.$sel.'>'.$aircraft->name.'</option>';
		}
		?>
		</select>
	</dd>

	<?php
	// List all of the custom PIREP fields
	if(!$pirepfields) $pirepfields = array();
	foreach($pirepfields as $field)
	{
	?>
		<dt><?=$field->title ?></dt>
		<dd><input type="text" name="<?=$field->name ?>" value="<?=$_POST[$field->name] ?>" /></dd>
	<?php
	}
	?>
	
	<dt>Flight Time</dt>
	<dd><input type="text" name="flighttime" value="<?=$_POST['flighttime'] ?>" />
		<p>Enter as hours - "5.5" is five and a half hours</p></dd>
		
	<dt>Comment</dt>
	<dd><textarea name="comment" style="width: 100%"><?=$_POST['flighttime'] ?></textarea></dd>
	
	<dt></dt>
	<dd><input type="submit" name="submit_pirep" value="File Flight Report" /></dd>
</dl>

</form>