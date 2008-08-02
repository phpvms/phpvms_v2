<h3>Add Setting</h3>
<p>Add a custom PIREP field, which a pilot can enter information to when filing a PIREP.</p>
<form id="form" method="post" action="action.php?admin=pirepfields">
<dl>
	<dt>Field Name</dt>
	<dd><input type="text" name="title" value="" /></dd>
	
	<dt>Field Type</dt>
	<dd><select name="type">
		<option value="text">Text field</option>
		<option value="textarea">Text area</option>
		<option value="dropdown">Dropdown</option>
		</select>
	
	<dt>Field Values (separate multiples with a comma)</dt>
	<dd><input type="text" name="values" value="" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="addfield" />
		<input type="submit" name="submit" value="Add Field" /></dd>
</dl>
</form>