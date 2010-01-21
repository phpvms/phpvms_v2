<?php

include '../core/codon.config.php';

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$_POST = unserialize('a:50:{s:16:"FsPAskToRegister";s:3:"yes";s:8:"UserName";s:7:"PLS0001";s:8:"Password";s:4:"none";s:11:"CompanyName";s:9:"Pulse Air";s:9:"PilotName";s:12:"Simon Newman";s:8:"FlightId";s:6:"PLS998";s:16:"OnlineNetworkNbr";s:1:"0";s:10:"FlightDate";s:10:"2009-11-21";s:12:"AircraftName";s:26:"B737-600 - Virtual Cockpit";s:12:"AircraftType";s:3:"MEJ";s:13:"NbrPassengers";s:3:"107";s:11:"CargoWeight";s:9:"19998 lbs";s:4:"Mtow";s:10:"144000 lbs";s:19:"StartAircraftWeight";s:10:"142041 lbs";s:17:"EndAircraftWeight";s:10:"135762 lbs";s:17:"StartFuelQuantity";s:8:"20430 kg";s:15:"EndFuelQuantity";s:8:"17545 kg";s:17:"DepartureIcaoName";s:25:"VMMC - Macau Intl - Macau";s:15:"ArrivalIcaoName";s:25:"ZGKL - Liangjiang - China";s:18:"DepartureLocalHour";s:5:"11:21";s:16:"ArrivalLocalHour";s:5:"12:33";s:16:"DepartureGmtHour";s:8:"04:21:00";s:14:"ArrivalGmtHour";s:8:"05:34:00";s:14:"TotalBlockTime";s:8:"01:13:16";s:19:"TotalBlockTimeNight";s:8:"00:00:00";s:16:"TotalAirbornTime";s:8:"01:06:28";s:17:"TotalTimeOnGround";s:8:"00:07:43";s:13:"TotalDistance";s:6:"267 Nm";s:11:"MaxAltitude";s:7:"10006ft";s:11:"CruiseSpeed";s:6:"258 kt";s:15:"CruiseMachSpeed";s:4:"0.40";s:18:"CruiseTimeStartSec";s:3:"206";s:17:"CruiseTimeStopSec";s:4:"3709";s:15:"CruiseFuelStart";s:8:"19705 kg";s:14:"CruiseFuelStop";s:8:"17680 kg";s:12:"LandingSpeed";s:6:"116 kt";s:12:"LandingPitch";s:4:"8.43";s:20:"TouchDownVertSpeedFt";s:7:"-203.08";s:17:"CaptainSentMayday";s:1:"0";s:9:"CrashFlag";s:1:"0";s:12:"FlightResult";s:7:"Perfect";s:17:"PassengersOpinion";s:3:"100";s:21:"PassengersOpinionText";s:142:"-Were in a better mood because they had food.<br>
-Were pleased by the music on ground.  A very nice addition to their flying experience.<br>
";s:11:"FailureText";s:0:"";s:14:"CasualtiesText";s:0:"";s:14:"PilotBonusText";s:252:"You made a very nice landing. (+50)<br>
Perfect Flight, no problems and very satisfied passengers. (+150)<br>
You landed at the scheduled airport. (+30)<br>
Bad weather conditions during take-off, but a safe landing and satisfied passengers. (+26)<br>
";s:11:"BonusPoints";s:3:"256";s:17:"PilotPenalityText";s:118:"You authorized food/drink to be served too late in the flight and it was interrupted by arrival procedures. (-50)<br>
";s:14:"PenalityPoints";s:2:"50";s:20:"BitsPenalityDisabled";s:5:"12929";}');

MainController::Run('acars', 'fspax');