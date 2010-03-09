<?php

include '../core/codon.config.php';

//$_GET = unserialize('s:145:"lat=40.622543&long=-73.786499&GS=0&Alt=16&IATA=VMS4567&pnumber=VMS0001&depaptICAO=KJFK&destaptICAO=KLGA&Ph=1&detailph=1&cargo=0&Regist=&Online=No";');

//MainController::Run('acars', 'fsacars');


# PIREP file


// part 1

$_GET = unserialize('a:18:{s:5:"pilot";s:10:"VMSVMS0001";s:4:"date";s:10:"2010/01/03";s:4:"time";s:8:"18:09:00";s:8:"callsign";s:0:"";s:3:"reg";s:6:"N845MJ";s:6:"origin";s:4:"KJFK";s:4:"dest";s:4:"KBOS";s:3:"alt";s:4:"KBOS";s:9:"equipment";s:4:"E145";s:4:"fuel";s:4:"1638";s:8:"duration";s:5:"00:20";s:8:"distance";s:2:"56";s:7:"version";s:4:"4015";s:4:"more";s:1:"0";s:3:"log";s:889:"[2010/01/03 18:09:00]*Flight IATA:VMS1000*Pilot Number:VMS0001*Company ICAO:VMS*Aircraft Type:E145*PAX:115*Aircraft Registration:N845MJ*Departing Airport: KJFK*Destination Airport: KBOS*Alternate Airport:KBOS*Online: No*Route:DIRECT*Flight Level:180*18:09  Zero fuel Weight: 54844 Lbs, Fuel Weight: 19448 Lbs*18:14  Parking Brakes off*18:14  Com1 Freq=128.30*18:16  VR= 209 Knots*18:16  V2= 212 Knots*18:16  Take-off*18:16  Take off Weight: 73999 Lbs*18:16  Wind: 308? @ 022 Knots Heading: 030?*18:16  POS N40? 38? 13?? W073? 46? 26?? *18:16  N11 89 N12 89*18:16  TOC*18:16  Fuel Weight: 19152 Lb*18:16  Gear Up: 221 Knots*18:19  Flaps:1 at 208 Knots*18:19  Flaps:0 at 202 Knots*18:26  Gear Down: 283 Knots*18:26  Flaps:2 at 283 Knots*18:26  Gear Up: 280 Knots*18:28  Flaps:3 at 177 Knots*18:29  Gear Down: 164 Knots*18:29  Flaps:4 at 163 Knots*18:29  Flaps:5 at 160 Knots*18:31  Wind:303?";s:6:"module";s:5:"acars";s:6:"action";s:7:"fsacars";s:4:"page";s:7:"fsacars";}');

MainController::Run('acars', 'fsacars');

$_GET = unserialize('a:18:{s:5:"pilot";s:10:"VMSVMS0001";s:4:"date";s:10:"2010/01/03";s:4:"time";s:8:"18:09:00";s:8:"callsign";s:0:"";s:3:"reg";s:6:"N845MJ";s:6:"origin";s:4:"KJFK";s:4:"dest";s:4:"KBOS";s:3:"alt";s:4:"KBOS";s:9:"equipment";s:4:"E145";s:4:"fuel";s:4:"1638";s:8:"duration";s:5:"00:20";s:8:"distance";s:2:"56";s:7:"version";s:4:"4015";s:4:"more";s:1:"1";s:3:"log";s:463:"@020 Knots Heading: 084? Ground Speed: 148 Knots Altitude 1148 ft*18:33  TouchDown:Rate -215 ft/min Speed: 119 Knots*18:33  Land*18:33  Wind:308?@023 Knots*18:33  Heading: 111?*18:33  Flight Duration: 00:17 *18:33  Landing Weight: 72688 Lbs*18:33  POS N40? 38? 29?? W073? 48? 06?? *18:34  Parking brakes on*18:34  Block to Block Duration: 00:20 *18:34  Final Fuel: 17810 Lbls*18:34  Spent Fuel: 1638 Lbls*18:34  Flight Length: 56 NM*18:34  TOD Land Length: 56 NM*";s:6:"module";s:5:"acars";s:6:"action";s:7:"fsacars";s:4:"page";s:7:"fsacars";}');

MainController::Run('acars', 'fsacars');