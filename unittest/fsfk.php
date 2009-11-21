<?php

include '../core/codon.config.php';

echo '<pre>';

$_REQUEST = unserialize('a:6:{s:5:"DATA1";s:14:"FLKEEPER|3.0.1";s:5:"DATA2";s:11:"BEGINFLIGHT";s:5:"DATA3";s:1324:"VMA0001|nabeel@nsslive.net|4567|AIRBUS A320|VMS|LFPG~TSU~TABOV~VADOM~CHW~ANG~NTS~ERIGA~DEGIS~TUROP~LOTEE~STG~DEMOS~ASMAR~VERAM~PECKY~LIDRO~SNT~NELSO~ROSTA~NORED~EDUMO~GAMBA~KENOX~SAGRO~DIGUN~POKSI~IRELA~PAKER~BUTUX~FIVZE~ARUVO~NUTRE~PULLS~HOLMA~SUNDE~WAYDE~LACKI~HUBER~DOWNT~CHAMP~BERGH~ANNGO~ELCAM~FONDE~OWENZ~GEENE~VOGEL~GLINN~TAAPS~GEDIC~BOUNO~CREEL~KJFK|N48 59.8956 E2 36.0990|401|||130243|2098|266|07603|14|IFR|6075||265|48.9925~48.7522~48.6397~48.5517~48.4745~47.5353~47.1565~46.8560~46.2560~46.0358~44.6552~42.9227~41.9220~39.3702~36.7700~34.5248~33.6700~33.0875~31.6763~28.2535~24.6363~22.9167~18.9583~14.7747~10.5833~9.6565~11.8567~14.0000~15.3333~18.0000~25.0000~27.3333~28.7853~29.8760~31.6517~31.8922~32.4017~33.4863~34.5762~35.9095~37.5167~39.1260~39.2283~39.5927~39.7212~39.8207~39.8430~39.9712~40.0065~40.0928~40.1410~40.3592~40.4417~40.6202|2.6053~2.1133~1.6427~1.2692~0.9855~-0.8600~-1.6077~-2.3578~-3.3527~-3.7080~-5.8352~-8.4218~-9.3572~-11.5905~-13.6718~-15.3360~-15.9430~-16.3533~-17.4542~-20.0000~-22.4747~-23.6000~-26.0570~-28.4893~-30.8580~-31.3690~-34.3867~-37.4333~-40.0000~-45.3747~-60.0000~-65.0000~-68.5590~-68.7422~-69.0617~-69.0905~-69.1883~-69.3743~-69.9850~-70.7382~-71.6763~-72.0583~-72.1420~-72.5717~-72.7217~-72.8248~-72.8562~-73.0022~-73.0407~-73.1418~-73.2100~-73.4542~-73.5517~-73.7852";s:5:"DATA4";s:0:"";s:7:"VMSAUTH";s:17:"22|1|67.87.69.128";s:9:"PHPSESSID";s:26:"ka39eeckdjn0nb1sf0v5ks3iv0";}');

//MainController::Run('FSFK', 'acars');



$_REQUEST = unserialize('a:6:{s:5:"DATA1";s:14:"FLKEEPER|3.0.1";s:5:"DATA2";s:7:"MESSAGE";s:5:"DATA3";s:1:"1";s:5:"DATA4";s:225:"[11/21/2009 17:35Z]
ACARS Mode: 2 Aircraft Reg: .......N4567
Msg Label: PB Block ID: 01 Msg No: M01A
Flight ID: 4567
Message:
BRK ON
/ALT 401
/HDG 266
/HDT 265
/IAS 0 /TAS 0
/WND 07603 /OAT 14 /TAT 14
/FOB 9988

";s:7:"VMSAUTH";s:17:"22|1|67.87.69.128";s:9:"PHPSESSID";s:26:"ka39eeckdjn0nb1sf0v5ks3iv0";}');


//MainController::Run('FSFK', 'acars');

$_REQUEST['DATA2']='<FLIGHTDATA>
        <PilotID>VMA0001</PilotID>
        <PilotName>Nabeel Shahzad</PilotName>
        <AircraftTitle>Beech King Air 350 Paint3</AircraftTitle>
        <AircraftType>N4567</AircraftType>
        <AircraftTailNumber>N350KA</AircraftTailNumber>
        <AircraftAirline></AircraftAirline>
        <FlightNumber></FlightNumber>
        <FlightLevel>1700</FlightLevel>
        <FlightType>IFR</FlightType>~
        <Passenger></Passenger>
        <Cargo></Cargo>
        <ZFW>10110</ZFW>
        <OriginICAO>KJFK - Kennedy Intl</OriginICAO>
        <OriginGate></OriginGate>
        <OriginRunway>04L</OriginRunway>
        <OriginTransitionAltitude>18000</OriginTransitionAltitude>
        <DestinationICAO>KLGA - La Guardia</DestinationICAO>
        <DestinationGate>Gate C 11</DestinationGate>
        <DestinationRunway>31</DestinationRunway>
        <DestinationTransitionAltitude>18000</DestinationTransitionAltitude>
        <AlternateICAO></AlternateICAO>
        <SID></SID>
        <STARS></STARS>
        <FlightDistance>11</FlightDistance>
        <RouteDistance>11</RouteDistance>
        <OUTTime>21.11.2009 14:19</OUTTime>
        <OFFTime>21.11.2009 14:20</OFFTime>
        <ONTime>21.11.2009 14:24</ONTime>
        <INTime>21.11.2009 14:25</INTime>
        <DayFlightTime>00:04</DayFlightTime>
        <NightFlightTime>00:00</NightFlightTime>
        <BlockTime>00:06</BlockTime>
        <FlightTime>00:04</FlightTime>
        <BlockFuel>0</BlockFuel>
        <FlightFuel>0</FlightFuel>
        <TOIAS>144</TOIAS>
        <LAIAS>112</LAIAS>
        <ONVS>-506.76</ONVS>
        <FlightScore>57.50%</FlightScore>
        <FLIGHTPLAN>
        <![CDATA[
        1|KJFK|Airport|14:20|3604|136|-82|46|171/12|12
2|KLGA|Airport|14:24|3604|123|-104|317|258/18|11

        ]]>
        </FLIGHTPLAN>
        <COMMENT>
        <![CDATA[

        ]]>
        </COMMENT>
        <FLIGHTCRITIQUE>
        <![CDATA[
        Landing lights off during Takeoff                                          | -5.0%
Strobe lights off during Takeoff                                           | -5.0%
Landing lights off below FL100                                             | -5.0%
Wrong altimeter setting during Landing                                     | -10.0%
Landing lights off during touchdown                                        | -5.0%
Strobe lights off during touchdown                                         | -5.0%
Hard touchdown                                                             | -7.5%
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
Total Score for this flight                                                | 57.5%
Landing rating                                                             | Very Bad
Pilot rating                                                               | Moderate

        ]]>
        </FLIGHTCRITIQUE>
        <FLIGHTMAPS>
                <FlightMapJPG></FlightMapJPG>
                <FlightMapWeatherJPG></FlightMapWeatherJPG>
                <FlightMapTaxiOutJPG></FlightMapTaxiOutJPG>
                <FlightMapTaxiInJPG></FlightMapTaxiInJPG>
                <FlightMapVerticalProfileJPG></FlightMapVerticalProfileJPG>
                <FlightMapLandingProfileJPG></FlightMapLandingProfileJPG>
        </FLIGHTMAPS>
</FLIGHTDATA>';

MainController::Run('FSFK', 'pirep');
