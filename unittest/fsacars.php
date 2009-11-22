<?php

include '../core/codon.config.php';

$_GET = unserialize('s:145:"lat=40.622543&long=-73.786499&GS=0&Alt=16&IATA=VMS4567&pnumber=VMS0001&depaptICAO=KJFK&destaptICAO=KLGA&Ph=1&detailph=1&cargo=0&Regist=&Online=No";');

//MainController::Run('acars', 'fsacars');


# PIREP file


// part 1

$_GET = unserialize('a:18:{s:5:"pilot";s:10:"VMSVMS0001";s:4:"date";s:10:"2009/11/22";s:4:"time";s:8:"02:01:00";s:8:"callsign";s:0:"";s:3:"reg";s:5:"N4567";s:6:"origin";s:4:"KJFK";s:4:"dest";s:4:"KLGA";s:3:"alt";s:0:"";s:9:"equipment";s:0:"";s:4:"fuel";s:2:"00";s:8:"duration";s:5:"00:06";s:8:"distance";s:2:"12";s:7:"version";s:4:"4015";s:4:"more";s:1:"0";s:3:"log";s:889:"[2009/11/22 02:01:00]*Flight IATA:VMS4567*Pilot Number:VMS0001*Company ICAO:VMS*Aircraft Registration:N4567*Departing Airport: KJFK*Destination Airport: KLGA*Online: No*Route:DIRECT*Flight Level:040*02:01  Zero fuel Weight: 10118 Lbs, Fuel Weight: 3602 Lbs*02:02  Parking Brakes off*02:02  Com1 Freq=128.30*02:03  VR= 142 Knots*02:03  V2= 148 Knots*02:03  Take-off*02:03  Take off Weight: 13720 Lbs*02:03  Wind: 000? @ 000 Knots Heading: 035?*02:03  POS N40? 38? 03?? W073? 46? 25?? *02:03  N11 99 N12 99*02:03  TOC*02:03  Fuel Weight: 3603 Lb*02:05  Flaps:1 at 209 Knots*02:05  Flaps:2 at 209 Knots*02:07  TouchDown:Rate -378 ft/min Speed: 143 Knots*02:08  Land*02:08  Wind:000?@000 Knots*02:08  Heading: 174?*02:08  Flight Duration: 00:05 *02:08  Landing Weight: 13720 Lbs*02:08  POS N40? 46? 43?? W073? 52? 23?? *02:08  Parking brakes on*02:08  Block to Block Duration: 00:06 *02:08  Fi";s:6:"module";s:5:"acars";s:6:"action";s:7:"fsacars";s:4:"page";s:7:"fsacars";}');

MainController::Run('acars', 'fsacars');

$_GET = unserialize('a:18:{s:5:"pilot";s:10:"VMSVMS0001";s:4:"date";s:10:"2009/11/22";s:4:"time";s:8:"02:01:00";s:8:"callsign";s:0:"";s:3:"reg";s:5:"N4567";s:6:"origin";s:4:"KJFK";s:4:"dest";s:4:"KLGA";s:3:"alt";s:0:"";s:9:"equipment";s:0:"";s:4:"fuel";s:2:"00";s:8:"duration";s:5:"00:06";s:8:"distance";s:2:"12";s:7:"version";s:4:"4015";s:4:"more";s:1:"1";s:3:"log";s:105:"nal Fuel: 3602 Lbls*02:08  Spent Fuel: 00 Lbls*02:08  Flight Length: 12 NM*02:08  TOD Land Length: 12 NM*";s:6:"module";s:5:"acars";s:6:"action";s:7:"fsacars";s:4:"page";s:7:"fsacars";}';

MainController::Run('acars', 'fsacars');