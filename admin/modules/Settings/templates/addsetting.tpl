<h2>Add Setting</h2>
<form id="form" action="action.php?admin=settings" method="post">
<p>Name's are converted to upper case. Values of "true" or "false" are converted to boolean, and the setting above will have "Enabled" or "Disabled" as options</p>
<dl>
	<dt>Setting Name:</dt>
	<dd><input name="name" type="text" value="" /></dd>
	
	<dt>Setting Value:</dt>
	<dd><input name="value" type="text" value="" /></dd>
	
	<dt>Description</dt>
	<dd><input name="descrip" type="text" value="" /></dd>
	
	<dt></dt>
	<dd><input type="submit" name="submit" value="Add Setting" /></dd>
</form>