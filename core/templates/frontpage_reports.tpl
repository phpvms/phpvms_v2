<?php
if(!$reports)
{
	echo 'No reports have been filed';
	return;
}

foreach($reports as $report)
{
?>
<p><a href="<?php echo SITE_URL?>/index.php/pireps/viewreport/<?php echo $report->pirepid;?>">#<?php echo $report->pirepid
	. ' - ' . $report->code.$report->flightnum?></a> - <a href="<?php echo SITE_URL?>/index.php/profile/view/<?php echo $report->pilotid?>"><?php echo $report->firstname . ' ' . $report->lastname?></a></p>
<?php
}
?>