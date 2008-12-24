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
	<td align="center" width="1%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/sitecms/editnews?id=<?php echo $news->id;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" />
		</a>
		<a href="<?php echo SITE_URL?>/admin/action.php/sitecms/viewnews" action="deleteitem" 
			id="<?php echo $news->id;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" />
		</a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>