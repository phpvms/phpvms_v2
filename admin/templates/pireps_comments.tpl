<?php
if(!$comments)
{
	echo '<p align="center"><strong>There are no comments for this PIREP</strong></p>';
	return;
}

foreach($comments as $comment)
{
?>
<p><strong><?php echo $comment->firstname. ' '. $comment->lastname ?> - <?php echo date(DATE_FORMAT, $comment->postdate);?>
</strong><br />
<?php echo $comment->comment; ?>
</p>
<hr>
<?php
}
?>