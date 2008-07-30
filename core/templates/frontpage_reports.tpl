<?php
if(!$reports)
{
	echo 'No reports have been filed';
	return;
}

foreach($reports as $report)
{
?>
<p><a href="<?=SITE_URL?>/index.php/pireps/viewreport/<?=$report->pilotid;?>">#<?=$report->pirepid
	. ' - ' . $report->code.$report->flightnum?></a> - <a href="<?=SITE_URL?>/index.php/pilotprofile/view/<?=$report->pilotid?>"><?=$report->firstname . ' ' . $report->lastname?></a></p>
<?php
}
?>