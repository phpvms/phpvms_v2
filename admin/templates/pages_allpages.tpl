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
	<td align="center"><?php echo $page->pagename; ?></td>
	<td align="center"><?php echo $page->postedby; ?></td>
	<td align="center"><?php echo $page->postdate; ?></td>
	<td align="center"><?php echo $page->filename; ?></td>
	<td align="center">
		<a href="index.php?admin=viewpages&action=editpage&pageid=<?php echo $page->pageid;?>"><img src="lib/images/edit.gif" alt="Edit" /></a>
		<a href="action.php?admin=viewpages&action=deletepage&pageid=<?php echo $page->pageid;?>" class="confirm"><img src="lib/images/delete.gif" alt="delete" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>