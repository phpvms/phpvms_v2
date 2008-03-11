<h3>Search Schedules</h3>
<form id="form" action="action.php?page=schedules" method="post">

<p><strong>Select a departure Airport</strong>
<select id="depicao" name="depicao">
	<option value="">Select a departure airport</option>
	<?=$depairports; ?>
</select>  

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

<input type="submit" name="submit" value="Find Flights" />
</p>
</form>
<hr>