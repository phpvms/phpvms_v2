<h3>Posted News</h3>
<dl>
<?php
foreach($allnews as $news)
{
?>
	<dt><?=$news->subject;?></dt>
	<dd>Posted by <?=$news->postedby;?> on <?=date('m/d/Y', $news->postingdate);?><br />
		<a href="action.php" module="viewnews" action="deleteitem" id="<?=$news->id;?>" class="ajaxcall">Delete</a></dd>
<?php
}
?>
</dl>	