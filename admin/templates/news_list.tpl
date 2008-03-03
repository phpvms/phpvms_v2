<h3>Posted News</h3>
<dl>
<?php
foreach($allnews as $news)
{
?>
	<dt><?=$news->subject;?></dt>
	<dd>Posted by <?=$news->postedby;?> on <?=date(DATE_FORMAT, $news->postdate);?><br />
		<a href="action.php?admin=viewnews" action="deleteitem" id="<?=$news->id;?>" class="ajaxcall">Delete</a></dd>
<?php
}
?>
</dl>	