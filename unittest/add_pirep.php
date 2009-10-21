<?php

include '../core/codon.config.php';


$schedules = SchedulesData::GetSchedules();
$idx = rand(0, count($schedules)-1);
$sched = $schedules[$idx];


$data = array('pilotid'=>1,
			  'code'=>$sched->code,
			  'flightnum'=>$sched->flightnum,
			  'depicao'=>$sched->depicao,
			  'arricao'=>$sched->arricao,
			  'aircraft'=>$sched->aircraft,
			  'flighttime'=>$sched->flighttime,
			  'submitdate'=>'NOW()',
			  'fuelused'=>6000,
			  'source'=>'manual',
			  'comment'=>'Test Flight');

$ret = PIREPData::FileReport($data);