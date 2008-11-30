<h3><?php echo $title ?></h3>
<p>Add a custom field, that a registrar can fill out on registration, or an admin can add information to.</p>
<form id="form" method="post" action="action.php?admin=customfields">
<dl>
	<dt>Title</dt>
	<dd><input type="text" name="title" value="<?php echo $field->title ?>" /></dd>
	
	<dt>Field Type</dt>
	<dd>
		<select name="fieldtype">
		<option value="text">Text</option>
		</select>
	</dd>
	
	<dt>Show in User Profile?</dt>
	<dd>
		<select name="public">
		<option value="yes" <?php if($field->public == 1) echo 'selected'; ?>>Yes</option>
		<option value="no" <?php if($field->public == 0) echo 'selected'; ?>>No</option>
		</select>
	</dd>
	
	<dt>Show During Registration</dt>
	<dd>
		<select name="showinregistration">
		<option value="yes" <?php if($field->showonregister == 1) echo 'selected'; ?>>Yes</option>
		<option value="no" <?php if($field->showonregister == 0) echo 'selected'; ?>>No</option>
		</select>
	</dd>
	
	<dt></dt>
	<dd><input type="hidden" name="fieldid" value="<?php echo $field->fieldid ?>" />
		<input type="hidden" name="action" value="<?php echo $action ?>" />
		<input type="submit" name="submit" value="<?php echo $title ?>" /></dd>
</dl>
</form>