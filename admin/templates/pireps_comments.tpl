<?php
if(!$comments)
{
	echo '<p align="center">There are no comments for this PIREP</p>';
	return;
}

foreach($comments as $comment)
{
?>
<p>Posted by <?php echo $comment->firstname. ' '. $comment->lastname ?> on <?php echo date(DATE_FORMAT, $comment->postdate);?>
<br />
<?php echo $comment->comment; ?>
</p>
<hr>
<?php
}
?>