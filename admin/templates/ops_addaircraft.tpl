<h3>Add Aircraft</h3>
<form id="form" action="action.php?admin=aircraft" method="post">
<dl>
<dt>Aircraft ICAO Code</dt>
<dd><input name="icao" type="text"></dd>

<dt>Aircraft Name (i.e B747-400)</dt>
<dd><input name="name" type="text" /></dd>

<dt>Full Name (Boeing 747-400 Combi)</dt>
<dd><input name="fullname" type="text" /></dd>

<dt>Range</dt>
<dd><input name="range" type="text" /></dd>

<dt>Weight</dt>
<dd><input name="weight" type="text" /></dd>

<dt>Cruise</dt>
<dd><input name="cruise" type="text" /></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="addaircraft" />
	<input type="submit" name="submit" value="Add Aircraft" />
</dd>
</dl>
</form>