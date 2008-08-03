<h3>Schedule Details</h3>
<div class="indent">
<strong>Flight Number: </strong> <?=$schedule->code.$schedule->flightnum ?><br />
<strong>Departure: </strong><?=$schedule->depname ?> (<?=$schedule->depicao ?>) at <?=$schedule->deptime ?><br />
<strong>Arrival: </strong><?=$schedule->arrname ?> (<?=$schedule->arricao ?>) at <?=$schedule->arrtime ?><br />
<?php
if($schedule->route!='')
{ ?>
<strong>Route: </strong><?=$schedule->route ?><br />
<?php
}?>
<br />
<strong>Schedule Frequency</strong>
<div id="schedgraph" align="center">Loading graph....</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{	$("#schedgraph").sparkline(<?=$scheddata; ?>, {width: '90%', height: '150px'}); });
</script>