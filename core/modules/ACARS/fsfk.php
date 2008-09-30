<?php

// this is the get data that is passed in by FSACARS
$fsa_data = array();

$fsa_data['name']='';
$fsa_data['pilot'] =  $_GET['pilot'];
$fsa_data['date'] =  $_GET['date'];
$fsa_data['time'] =  $_GET['time'];
$fsa_data['callsign'] =  $_GET['callsign'];
$fsa_data['reg'] =  $_GET['reg'];
$fsa_data['origin'] =  $_GET['origin'];
$fsa_data['dest'] =  $_GET['dest'];
$fsa_data['equipment'] =  $_GET['equipment'];
$fsa_data['fuel'] =  $_GET['fuel'];
$fsa_data['duration'] =  $_GET['duration'];
$fsa_data['distance'] =  $_GET['distance'];
$fsa_data['rep_url'] =  SITE_URL.'/index.php/ACARS/fsacars';
$fsa_data['more'] =  $_GET['more'];
$fsa_data['fsacars_log'] =  $_GET['log'];

ob_start();
print_r($_GET);

$con = ob_get_clean();
ob_end_clean();

$fe = fopen (dirname(__FILE__).'/log.txt', "a");
fwrite($fe, "[DEBUG ".date("d.m.y H:i:s")."] ".$con);
fclose($fe);
	

echo 'OK';
?>