<h3>Add Setting</h3>
<p>Add a custom field, that a registrar can fill out on registration, or an admin can add information to.</p>
<form id="form" method="post" action="action.php?admin=customfields">
<dl>
	<dt>Field Name</dt>
	<dd><input type="text" name="fieldname" value="" /></dd>
	
	<dt>Field Type</dt>
	<dd>
		<select name="fieldtype">
		<option value="text">Text</option>
		</select>
	</dd>
	
	<dt>Show in User Profile?</dt>
	<dd>
		<select name="public">
		<option value="yes">Yes</option>
		<option value="no">No</option>
		</select>
	</dd>
	
	<dt>Show During Registration</dt>
	<dd>
		<select name="showinregistration">
		<option value="yes">Yes</option>
		<option value="no">No</option>
		</select>
	</dd>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="addfield" />
		<input type="submit" name="submit" value="Add Field" /></dd>
</dl>
</form>