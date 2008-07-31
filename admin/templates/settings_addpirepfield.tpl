<h3>Add Setting</h3>
<p>Add a custom PIREP field, which a pilot can enter information to when filing a PIREP.</p>
<form id="form" method="post" action="action.php?admin=pirepfields">
<dl>
	<dt>Field Name</dt>
	<dd><input type="text" name="title" value="" /></dd>
		
	<dt></dt>
	<dd><input type="hidden" name="action" value="addfield" />
		<input type="submit" name="submit" value="Add Field" /></dd>
</dl>
</form>