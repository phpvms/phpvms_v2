<h3>Search Schedules</h3>
<form id="form" action="action.php?page=schedules" method="post">

<p><strong>Select a departure Airport</strong>
<select id="depicao" name="depicao">
<option value="">Select All</option>
<?php
if(!$depairports) $depairports = array();
foreach($depairports as $airport)
{
	echo '<option value="'.$airport->icao.'">'.$airport->icao.' ('.$airport->name.')</option>';
}
?>
	
</select>  

<p><strong>Select equipment: </strong> (optional): 
<select id="equipment" name="equipment">
	<option value="">Select equipment</option>
<?php

if(!$equipment) $equipment = array();
foreach($equipment as $equip)
{
	echo '<option value="'.$equip->id.'">'.$equip->name.'</option>';
}

?>
</select> 

<input type="hidden" name="action" value="findflight" />
<input type="submit" name="submit" value="Find Flights" />
</p>
</form>
<hr>