<?php

include '../core/codon.config.php';


$sql = "SELECT * FROM phpvms_pireps";
$results = DB::get_results($sql);

$time = '0';
foreach($results as $row)
{
	$time = Util::AddTime($time, $row->flighttime_old);
}

echo "Total Time (calculated): {$time}<br />";


$sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(flighttime))) AS t_time FROM phpvms_pireps";
$row = DB::get_row($sql);
DB::debug();

echo "Time from MySQL: {$row->t_time}<br />";

echo ($row->t_time * 5.50);