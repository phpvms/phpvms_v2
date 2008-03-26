<h3>Search Schedules</h3>
<form id="form" action="action.php?page=schedules" method="post">

<p><strong>Select a departure Airport</strong>
<select id="depicao" name="depicao">
<option value="">Select a departure airport</option>
<?php
foreach($depairports as $airport)
{
	echo '<option value="'.$airport->icao.'">'.$airport->icao.' ('.$airport->name.')</option>';
}
?>
	
</select>  
<!--
<strong>Arrival Airports</strong>

<select id="arricao" name="arricao">
	<option value="">Select a departure airport</option>
	<?=$depairports; ?>
</select>  
</p>
<p><strong>Select equipment: </strong> (optional): 
<select id="equipment" name="equipment">
	<option value="">Select equipment</option>
	<?=$depairports; ?>
</select> 
-->
<input type="hidden" name="action" value="findflight" />
<input type="submit" name="submit" value="Find Flights" />
</p>
</form>
<hr>