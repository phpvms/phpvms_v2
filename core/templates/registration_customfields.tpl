<?php
/* Show any extra fields
 */
if($extrafields)
{
	foreach($extrafields as $field)
	{
?>
	<dt><?php echo $field->title; ?></dt>
	<dd><input type="text" name="<?php echo $field->fieldname; ?>" value="<?php echo  Vars::POST($field->fieldname);?>" /></dd>
<?php
	}
}
?>