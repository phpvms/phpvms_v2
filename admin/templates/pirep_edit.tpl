<h3>Edit a flight report</h3>
<?php
if(isset($message))
	echo '<div id="error">'.$message.'</div>';
?>
<form action="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewpending" method="post">
<dl>
	<dt>Pilot:</dt>
	<dd><strong><?php echo $pirep->firstname . ' ' . $pirep->lastname;?></strong></dd>
	
	<dt>Select Airline:</dt>
	<dd>
		<select name="code" id="code">
			<option value="">Select your airline</option>
			<?php
			foreach($allairlines as $airline)
			{
				$sel = ($pirep->code == $airline->code)?'selected':'';
					
				echo '<option value="'.$airline->code.'" '.$sel.'>'.$airline->name.'</option>';
			}
			?>
		</select>
	</dd>
	
	<dt>Enter Flight Number:</dt>
	<dd><input type="text" name="flightnum" value="<?php echo $pirep->flightnum ?>" /></dd>
	
	<dt>Select Departure Airport:</dt>
	<dd>
		<div id="depairport">
		<select id="depicao" name="depicao">
			<option value="">Select a departure airport</option>
			<?php
			foreach($allairports as $airport)
			{
				
				$sel = ($pirep->depicao == $airport->icao)?'selected':'';
				
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
				$sel = ($pirep->arricao == $airport->icao)?'selected':'';
				
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
			$sel = ($pirep->aircraftid == $aircraft->id) ? 'selected':'';
			echo '<option value="'.$aircraft->id.'" '.$sel.'>'.$aircraft->name.' - '.$aircraft->registration.'</option>';
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
		<dt><?php echo $field->title ?></dt>
		<dd>
		<?php
		
		// Determine field by the type
		$value = PIREPData::GetFieldValue($field->fieldid, $pirep->pirepid);
	
		if($field->type == '' || $field->type == 'text')
		{
		?>
			<input type="text" name="<?php echo $field->name ?>" value="<?php echo $value ?>" />
		<?php
		} 
		elseif($field->type == 'textarea')
		{
			echo '<textarea name="'.$field->name.'">'.$value.'</textarea>';
		}
		elseif($field->type == 'dropdown')
		{
			$values = explode(',', $field->options);
			
			echo '<select name="'.$field->name.'">';
			foreach($values as $fvalue)
			{
				if($value == $fvalue)
				{
					$sel = 'selected="selected"';
				}
				else	
				{
					$sel = '';
				}
				
				$value = trim($fvalue);
				echo '<option value="'.$fvalue.'" '.$sel.'>'.$fvalue.'</option>';
			}
			echo '</select>';		
		}
		?>
		
		</dd>
	<?php
	}
	?>
	
	<dt>Load</dt>
	<dd><input type="text" name="load" value="<?php echo $pirep->load; ?>" />
		<p>This is the load of this flight. It's automatically determined, though you can adjust it here</p></dd>
		
	<dt>Price</dt>
	<dd><input type="text" name="price" value="<?php echo $pirep->price; ?>" />
		<p>This is the price per load unit for this flight.</p></dd>
		
	<dt>Fuel Used</dt>
	<dd><input type="text" name="fuelused" value="<?php echo $pirep->fuelused; ?>" />
		<p>This is the fuel used on this flight in <?php echo Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit'))?></p></dd>	
		
	<dt>Fuel Price</dt>
	<dd><input type="text" name="fuelunitcost" value="<?php echo $pirep->fuelunitcost?>" />
		<p>This is the price of fuel, <?php echo Config::Get('MONEY_UNIT').' per '.Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit'))?>. If you change this, the total fuel cost amount below will be calculated and replaced</p></dd>
		
	<dt>Gross Revenue:</dt>
	<dd><?php echo FinanceData::FormatMoney($pirep->revenue); ?>
		<p>Change the load and price variables above to adjust this value.</p></dd>
	
	<dt>Total fuel cost</dt>
	<dd><?php echo FinanceData::FormatMoney($pirep->fuelprice); ?> 
		<p>Change the fuel cost above to edit this total price</p></dd>
		
	<dt>Expenses</dt>
	<dd><input type="text" name="expenses" value="<?php echo $pirep->expenses?>" />
		<p>Additional expenses for this flight (catering, cleaning, etc)</p></dd>
		
	<dt>Pilot Pay</dt>
	<dd><input type="text" name="pilotpay" value="<?php echo $pirep->pilotpay;?>" />
		<p>This is what the pilot will be paid, per hour, for this flight</p></dd>
		
	<dt>Total Revenue for flight:</dt>
	<dd><?php echo FinanceData::FormatMoney($pirep->revenue); ?> 
		<p></p></dd>
		
	<dt>Flight Time</dt>
	<dd><input type="text" name="flighttime" value="<?php echo $pirep->flighttime; ?>" />
		<p>Enter as hours - "5:30" is five hours and thirty minutes</p></dd>
		
	<dt>Comments</dt>
	<dd>
		<?php
		if(!$comments)
		{
			echo '<p>No comments</p>';
			$comments=array();
		}
		
		foreach($comments as $comment)
		{?>
			<p><?php echo $comment->comment; ?></p>
		<?php
		}
		?>
	</dd>
	
	<?php
	if($pirep->log != '')
	{
	?>
	<dt>Log File:</dt>
	<dd>
		<a href="#" onclick="$('#log').toggle(); return false;">View Log</a></li>
		<div id="log" style="display: none;">
		<?php
		# Simple, each line of the log ends with *
		# Just explode and loop.
		$log = explode('*', $pirep->log);
		foreach($log as $line)
		{
			echo $line .'<br />';
		}
		?>
		</div>
			
	</dd>
	<?php
	}
	?>
	
	<dt></dt>
	<dd><input type="hidden" name="pirepid" value="<?php echo $pirep->pirepid;?>" />
		<input type="hidden" name="action" value="editpirep" />
		<input type="submit" name="submit_pirep" value="Edit Flight Report" /></dd>
</dl>

</form>