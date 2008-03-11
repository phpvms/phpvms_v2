<h3>All Pages</h3>
<p><a id="dialog" class="jqModal" href="action.php?admin=addpage">Add a page</a></p>

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
	<td align="center"><?=$page->name; ?></td>
	<td align="center"><?=$page->postedby; ?></td>
	<td align="center"><?=$page->postdate; ?></td>
	<td align="center"><?=$page->filename; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=viewpages&action=editpage&pageid=<?=$page->pageid;?>">Options</a></td>
</tr>
<?php
}
?>
</tbody>
