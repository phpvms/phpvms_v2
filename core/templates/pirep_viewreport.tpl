<h3>View Pilot Report</h3>

<div>
<dl>
	<dt>Submitted on: </dt>
	<dd><?=$report->submitdate;?></dd>
	
	<dt>Route:</dt>
	<dd><?=$report->depicao . ' -> ' . $report->arricao;?></dd>
	
	<dt>Hours:</dt>
	<dd><?=$report->flighttime;?></dd>
</dl>
</div>
<?php
if($comments)
{
	echo '<div style="clear:both;"><h3>Comments</h3>';
	
	foreach($comments as $comment)
	{
?>
	<p><?=$comment->comment?> - By <?=$comment->firstname . ' ' .$comment->lastname?></p>
<?php
	}
	
	echo '</div>';
}
?>
<div style="clear:both;">
	<p>Map Here!</p>
</div>