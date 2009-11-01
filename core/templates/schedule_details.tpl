<h3>Schedule Details</h3>
<div class="indent">
<strong>Flight Number: </strong> <?php echo $schedule->code.$schedule->flightnum ?><br />
<strong>Departure: </strong><?php echo $schedule->depname ?> (<?php echo $schedule->depicao ?>) at <?php echo $schedule->deptime ?><br />
<strong>Arrival: </strong><?php echo $schedule->arrname ?> (<?php echo $schedule->arricao ?>) at <?php echo $schedule->arrtime ?><br />
<?php
if($schedule->route!='')
{ ?>
<strong>Route: </strong><?php echo $schedule->route ?><br />
<?php
}?>
<br />
<strong>Weather Information</strong>
<div id="<?php echo $schedule->depicao ?>" class="metar">Getting current METAR information for <?php echo $schedule->depicao ?></div>
<div id="<?php echo $schedule->arricao ?>" class="metar">Getting current METAR information for <?php echo $schedule->arricao ?></div>
<br />
<strong>Schedule Frequency</strong>
<div align="center">
<?php
	$data = array();
	$labels = array();
	
	foreach($scheddata as $month=>$count)
	{
		$data[] = $count;
		$labels[] = $month;
	}
	
	$chart = new ChartGraph('gchart', 'ls', 600, 150);
	$chart->AddData($data, $labels);
	
	echo '<img align="center" src="'.$chart->GenerateGraph().'" />';
?>
</div>