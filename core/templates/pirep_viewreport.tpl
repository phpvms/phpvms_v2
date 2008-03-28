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
<div style="clear:both;">
	<p>Map Here!</p>
</div>