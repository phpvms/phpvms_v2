<h3>File a Flight Report</h3>

<form action="?page=filepirep" action="post">
<dl>
	<dt>Pilot:</dt>
	<dd><?=$pilot;?></dd>
	
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

	<dt>Select Departure Airport:</dt>
	<dd>
		<div id="depairport">Select an airline from above</div>
	</dd>
	
	<dt>Select Arrival Airport:</dt>
	<dd>
		<div id="arrairport">Select a departure airport from above</div>
	</dd>
	
	<dt>Flight Time</dt>
	<dd><input type="text" name="flighttime" />
		<p>Enter as hours - "5.5" is five and a half hours</p></dd>
</dl>

</form>