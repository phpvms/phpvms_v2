<h3>All Pages</h3>

<?php
if(!$allpages)
{
	echo '<p>No pages have been added!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Page Name</th>
	<th>Updated By</th>
	<th>Update Date</th>
	<th>Filename</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpages as $page)
{
?>
<tr>
	<td align="center"><?=$page->pagename; ?></td>
	<td align="center"><?=$page->postedby; ?></td>
	<td align="center"><?=$page->postdate; ?></td>
	<td align="center"><?=$page->filename; ?></td>
	<td align="center"><a href="?admin=viewpages&action=editpage&pageid=<?=$page->pageid;?>"><img src="lib/images/edit.gif" alt="Edit" /></a> 
	<a href="?admin=viewpages&action=deletepage&pageid=<?=$page->pageid;?>" class="confirm"><img src="lib/images/delete.gif" alt="delete" /></a> </td>
</tr>
<?php
}
?>
</tbody>
</table>