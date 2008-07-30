<?php
if(!$comments)
{
	echo '<p align="center">There are no comments for this PIREP</p>';
	return;
}

foreach($comments as $comment)
{
?>
<p>Posted by <?=$comment->firstname. ' '. $comment->lastname ?> on <?=date(DATE_FORMAT, $comment->postdate);?>
<br />
<?=$comment->comment; ?>
</p>
<hr>
<?php
}
?>