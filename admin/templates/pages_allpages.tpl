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
	<th>File/Link</th>
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
	<td align="center"><a href="<?php echo SITE_URL?>/index.php/pages/<?php echo $page->filename; ?>"><?php echo $page->filename; ?></a></td>
	<td align="center" width="1%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/sitecms/editpage?pageid=<?php echo $page->pageid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		<a href="<?php echo SITE_URL?>/admin/action.php/viewpages?action=deletepage&pageid=<?php echo $page->pageid;?>" 
				class="confirm">
				<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="delete" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>