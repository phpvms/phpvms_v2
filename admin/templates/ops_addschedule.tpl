<h3>Add Schedule</h3>
<form id="form" action="action.php?admin=schedules" method="post">
<p><strong>Code: </strong><input type="text" name="code" value="<?=PID_PREFIX;?>" />
	<strong>Flight Number: </strong><input type="text" name="flightnumber" value="" />
</p>

<dt></dt>
<dd><div id="statusbox"></div></dd>
<dt>Airport Name</dt>
<dd><input id="airportname" name="name" type="text" /></dd>

<dt>Country Name</dt>
<dd><input id="airportcountry" name="country" type="text" /></dd>

<dt>Latitude</dt>
<dd><input id="airportlat" name="lat" type="text" /></dd>

<dt>Longitude</dt>
<dd><input id="airportlong" name="long" type="text" /></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="addairport" />
	<input type="submit" name="submit" value="Add Airport" />
</dd>
</dl>
</form>