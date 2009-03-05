<div id="awardslist">
<h3>Pilot Awards</h3>
<?php
if(!$allawards)
{
	echo 'This pilot has no awards!</div>';
	return;
}


print_r($allawards);
?>
</div>