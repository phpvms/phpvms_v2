<?php

include '../core/codon.config.php';
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$arr = file(dirname(__FILE__).'/times.txt');

$total_time = 0;
foreach($arr as $time)
{
	$total_time = Util::AddTime($total_time, $time);
}

echo "Total Time (calculated, from file): {$total_time}<br />";

$sql = "SELECT * FROM phpvms_pireps";
$results = DB::get_results($sql);

$time = '0';
foreach($results as $row)
{
	$time = Util::AddTime($time, $row->flighttime);
}

echo "Total Time (calculated): {$time}<br />";


$sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(REPLACE(`flighttime`, '.', ':'))) AS t_time FROM phpvms_pireps";
$row = DB::get_row($sql);
DB::debug();

echo "Time from MySQL: {$row->t_time}<br />";

echo ($row->t_time * 5.50);