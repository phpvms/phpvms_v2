<h3>Awards</h3>
<?php
if(!$awards){ echo 'No awards have been added yet!'; return;}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Award</th>
	<th>Description</th>
	<th>Image</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($awards as $aw)
{
?>
<tr>
	<td align="center"><?php echo $aw->name; ?></td>
	<td align="center"><?php echo $aw->descrip; ?></td>
	<td align="center"><?php echo $aw->image; ?></td>
	<td align="center" width="1%" nowrap>
	
		<a id="dialog" class="jqModal" 
			href="<?php echo SITE_URL?>/admin/action.php/pilotranking/editaward?awardid=<?php echo $aw->awardid;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
			
		<a href="<?php echo SITE_URL?>/admin/action.php/pilotranking/awards" action="deleteaward" 
			id="<?php echo $aw->awardid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a></td>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>