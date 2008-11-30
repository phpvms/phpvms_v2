<h3>Posted News</h3>
<?php
if(!$allnews)
{
	echo '<p>No news items have been posted yet</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Subject</th>
	<th>Poster</th>
	<th>Posted Date</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allnews as $news)
{
?>
<tr>
	<td align="center"><?php echo $news->subject;?></td>
	<td align="center"><?php echo $news->postedby;?></td>
	<td align="center"><?php echo date(DATE_FORMAT, $news->postdate);?></td>
	<td align="center">
		<a href="index.php?admin=editnews&id=<?php echo $news->id;?>"><img src="lib/images/edit.gif" alt="Edit" /></a>
		<a href="action.php?admin=viewnews" action="deleteitem" id="<?php echo $news->id;?>" class="ajaxcall"><img src="lib/images/delete.gif" alt="Delete" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>