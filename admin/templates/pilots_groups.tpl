<?php
if(!$allgroups)
{
	echo 'This user is not in any groups!';
	return;
}
?>

<dl>
<dt>Group Name</dt>
<dd><strong>Options</strong></dd>
<?php 
foreach($allgroups as $group)
{
?>
	<dt><?=$group->groupname;?></dt>
	<dd><a href="action.php?admin=viewpilots" action="viewoptions" id="<?=$group->groupid;?>" class="dialogajax">Remove</a></dd>
	
<?php
}
?>
</dl>