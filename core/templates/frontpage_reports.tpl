<?php

if(!$reports)
{
	echo 'No reports have been filed';
	return;
}

foreach($reports as $report)
{
?>
<p><a href="?page=viewreport&pirepid=<?=$report->pirepid?>">Flight #<?=$report->pirepid . ' - ' . $report->code.$report->flightnum?></a> - <?=$report->firstname . ' ' . $report->lastname?></p>
<?php
}
?>