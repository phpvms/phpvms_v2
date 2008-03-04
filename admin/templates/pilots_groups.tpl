<?php
if(!$pilotgroups)
{
	echo 'This user is not in any groups!<br />';
}
else
{
?>
<h3>Current Pilot Groups</h3>
<dl>
<dt>Group Name</dt>
<dd><strong>Options</strong></dd>
<?php 
	foreach($pilotgroups as $group)
	{
?>
	<dt><?=$group->name;?></dt>
	<dd><a href="action.php?admin=viewpilots" userid="<?=$userid;?>" action="removegroup" id="<?=$group->groupid;?>" class="pilotgroupajax">Remove</a></dd>
	
<?php
	}
}
?>
</dl>

<h3>Add to Group</h3>

<?php 
$total = count($freegroups);

if($total == 0)
{
	echo 'No groups to add to';
	return;
}
?>
<form id="selectpilotgroup" action="action.php?admin=viewpilots" method="post">

<dl>
	<dt>Select Group:</dt>
	<dd><select name="groupname">
		<?php
		for($i=0;$i<$total;$i++)
		{
			echo '<option value="'.$freegroups[$i].'">'.$freegroups[$i].'</option>';
		}
		?>
		</select></dd>

	<dt></dt>
	<dd><input type="hidden" name="userid" value="<?=Vars::GET('userid');?>" />
		<input type="hidden" name="action" value="addgroup" />
		<input type="submit" name="submit" value="Add to Group" /></dd>
</dl>
</form>