<dl>
<?php
foreach($allnews as $news)
{
?>
	<dt><?=$news->subject;?></dt>
	<dd>Posted by <?=$news->postedby;?> on <?=date('mdY', $news->postdate;?><br />
		<a href="action.php" module="viewnews" action="deleteitem" id="<?=$news->id;?>" class="ajaxcall">Delete</a></dd>
<?php
}
?>
</dl>	