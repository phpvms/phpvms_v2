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
		<div id="depairports">Select an airline from above</div>
	</dd>

</dl>

</form>