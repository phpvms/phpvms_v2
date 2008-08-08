<h3><?=$title ?></h3>
<p>Add a custom PIREP field, which a pilot can enter information to when filing a PIREP.</p>
<form id="form" method="post" action="action.php?admin=pirepfields">
<dl>
	<dt>Field Name</dt>
	<dd><input type="text" name="title" value="<?=$field->title ?>" /></dd>
	
	<dt>Field Type</dt>
	<dd><?php
		$sel = $field->type;
		$$sel = 'selected';
		?>
		<select name="type">
			<option value="text" <?=$text ?>>Text field</option>
			<option value="textarea" <?=$textarea ?>>Text area</option>
			<option value="dropdown" <?=$dropdown ?>>Dropdown</option>
		</select>
	
	<dt>Field Values (separate multiples with a comma)</dt>
	<dd><input type="text" name="options" value="<?=$field->options ?>" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="fieldid" value="<?=$field->fieldid;?>" />
		<input type="hidden" name="action" value="<?=$action ?>" />
		<input type="submit" name="submit" value="<?=$title ?>" /></dd>
</dl>
</form>