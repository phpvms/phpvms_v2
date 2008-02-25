<h1>Custom Fields</h1>
<?php
if(!$allfields)
{
	echo 'You have not added any custom fields';
	return;
}
?>

<dl>
	<dt>Field Name</dt>
	<dd><strong>Options</strong></dd>
<?php
foreach($allfields as $field)
{
?>
	<dt><?=$field->fieldname;?></dt>
	<dd><a href="#">Delete</a></dd>
<?php
}
?>
</dl>
<br /><br />
<hr>