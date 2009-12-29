<?php

include '../core/codon.config.php';
echo '<pre>';

$schedules = SchedulesData::findSchedules(array());
$idx = rand(0, count($schedules)-1);
$sched = $schedules[$idx];

echo '<strong>Filing report...</strong><br />';
$data = array(
	'pilotid'=>1,
		  'code'=>$sched->code,
		  'flightnum'=>$sched->flightnum,
		  'depicao'=>$sched->depicao,
		  'arricao'=>$sched->arricao,
		  'aircraft'=>$sched->aircraft,
		  'flighttime'=>$sched->flighttime,
		  'submitdate'=>'NOW()',
		  'fuelused'=>6000,
		  'source'=>'unittest',
		  'comment'=>'Test Flight',
);

$ret = PIREPData::fileReport($data);
if($ret == false)
{
	echo PIREPData::$lasterror;
}

$pirepid = DB::$insert_id;
$report_info = PIREPData::findPIREPS(array('p.pirepid'=>$pirepid));
echo '<br />';
print_r($report_info);

echo '<strong>Deleting...</strong><br />';
PIREPData::deletePIREP($pirepid);
$report_info = PIREPData::findPIREPS(array('p.pirepid'=>$pirepid));

if(!$report_info)
{
	echo 'PIREP deleted';
}