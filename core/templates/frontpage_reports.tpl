<?php
if(!$reports)
{
	echo 'No reports have been filed';
	return;
}

foreach($reports as $report)
{
?>
<p><a href="?page=viewreport&pirepid=<?=$report->pilotid;?>">#<?=$report->pirepid . ' - ' . $report->code.$report->flightnum?></a> - <a href="?page=pilotprofile&pilotid=<?=$report->pilotid?>"><?=$report->firstname . ' ' . $report->lastname?></a></p>
<?php	
}
?>