<?php
/* Show any extra fields
 */
if($extrafields)
{
	foreach($extrafields as $field)
	{
?>
	<dt><?php echo $field->title; ?></dt>
	<dd><input type="text" name="<?=$field->fieldname; ?>" value="<?= Vars::POST($field->fieldname);?>" /></dd>
<?php
	}
}
?>