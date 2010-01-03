<h3>Your Stats</h3>

<h4>Pilot Report Totals</h4>
<div id="monthly_pireps_counts" style="width:100%;height:300px"></div>

<h4>Aircraft Flown Data</h4>
<div id="aircraft_flown_counts" style="width:500px;height:300px"></div>
<?php

/* monthly pirep data */
$i=0;
foreach($allmonthdata as $monthly)
{
	$monthly_rev_string .= "[{$i}, {$monthly->revenue}],";
	$monthly_total_string .= "[{$i}, {$monthly->total}],";
	$monthly_rev_labels .= "[{$i}, \"{$monthly->ym}\"],";
	$i++;
}

/* aircraft flown data */
$aircraft_data = array();
foreach($allaircraftdata as $data)
{
	$aircraft_data[] = "{label: \"{$data->aircraft}\", data: {$data->hours}}";
}

$aircraft_data = implode(',', $aircraft_data);
?>
<script type="text/javascript" src="<?php echo fileurl('lib/js/jquery.flot.min.js');?>"></script>
<script type="text/javascript" src="<?php echo fileurl('lib/js/jquery.flot.pie.pack.js');?>"></script>
<script type="text/javascript" src="<?php echo fileurl('lib/js/excanvas.min.js');?>"></script>
<script type="text/javascript">
var bar_options = { show: true, align: "center", barWidth: .7 };
var day_ticks = [[0,"Sunday"], [1,"Monday"], [2,"Tuesday"], [3, "Wednesday"], [4, "Thursday"], [5, "Friday"], [6, "Saturday"]];

$.plot($("#monthly_pireps_counts"), 
	[{color: "#CC3300", data: [<?=$monthly_total_string; ?>], bars: bar_options }],
	 { 
		xaxis: { ticks: [<?=$monthly_rev_labels; ?>] } 
	 }
);

$.plot($("#aircraft_flown_counts"), [<?php echo $aircraft_data;?>], 
{
	pie: { 
		show: true, 
		pieStrokeLineWidth: 1, 
		pieStrokeColor: '#FFF', 		
		showLabel: true,			
		//labelBackgroundOpacity: 0.55, 
		labelFormatter: function(serie){
			return serie.label+'<br/>'+Math.round(serie.percent)+'%';
		}
	},
	legend: { show: true,  position: "ne",  backgroundOpacity: 0 }
});
</script>