<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Jeffery Kobus
 * @copyright Copyright (c) 2010, Jeffery Kobus
 * @link http://www.fs-products.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */


class kACARS_Free extends CodonModule
{
	
	public function index()
	{
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
		{ 
			$postText = file_get_contents('php://input');		
			
			$rec_xml = trim(utf8_encode(file_get_contents('php://input')));
			$xml = simplexml_load_string($rec_xml);	
			
			if(!$xml)
			{
				$this->log("Invalid XML Sent: \n".$rec_xml, 'kacars');
				return;	
			}
			
			$this->log(print_r($this->xml->asXML(), true), 'kacars');
			
			$case = strtolower($this->xml->switch->data);
			switch($case)
			{
				case 'verify':		
					$results = Auth::ProcessLogin($xml->verify->pilotID, $xml->verify->password);		
					if ($results)
					{						
						$params = array('loginStatus' => '1');
						
						//echo 1;
					}
					else
					{
						$params = array('loginStatus' => '0');
						//echo 0;
					}
					
					$send = self::sendXML($params);
					
					break;
				
				case 'getbid':							
					
					$pilotid = PilotData::parsePilotID($this->xml->verify->pilotID);
					$pilotinfo = PilotData::getPilotData($pilotid);
					$biddata = SchedulesData::getLatestBid($pilotid);
					$aircraftinfo = OperationsData::getAircraftByReg($biddata->registration);
					
					if(count($biddata) == 1)
					{		
						if($aircraftinfo->enabled == 1)
						{
							$params = array(
								'flightStatus' 	   => '1',
								'flightNumber'     => $biddata->code.$biddata->flightnum,
								'aircraftReg'      => $biddata->registration,
								'aircraftICAO'     => $aircraftinfo->icao,
								'aircraftFullName' => $aircraftinfo->fullname,
								'flightLevel'      => $biddata->flightlevel,
								'aircraftMaxPax'   => $aircraftinfo->maxpax,
								'aircraftCargo'    => $aircraftinfo->maxcargo,
								'depICAO'          => $biddata->depicao,
								'arrICAO'          => $biddata->arricao,
								'route'            => $biddata->route,
								'depTime'          => $biddata->deptime,
								'arrTime'          => $biddata->arrtime,
								'flightTime'       => $biddata->flighttime,
								'flightType'       => $biddata->flighttype,
								'aircraftName'     => $aircraftinfo->name,
								'aircraftRange'    => $aircraftinfo->range,
								'aircraftWeight'   => $aircraftinfo->weight,
								'aircraftCruise'   => $aircraftinfo->cruise
								);					
						}
						else
						{	
							$params = array(
								'flightStatus' 	   => '3');		// Aircraft Out of Service.							
						}			
					}		
					else		
					{
						$params = array('flightStatus' => '2');	// You have no bids!								
					}
					
					$send = $this->sendXML($params);
					
					break;
				
				case 'getflight':
					
					$flightinfo = SchedulesData::getProperFlightNum($this->xml->pirep->flightNumber);
					
					$params = array(
						's.code' => $flightinfo['code'],
						's.flightnum' => $flightinfo['flightnum'],
						's.enabled' => 1,
					);
					
					$biddata = SchedulesData::findSchedules($params, 1);
					$aircraftinfo = OperationsData::getAircraftByReg($biddata[0]->registration);
					
					if(count($biddata) == 1)
					{		
						$params = array(
							'flightStatus' 	   => '1',
							'flightNumber'     => $biddata[0]->code.$biddata[0]->flightnum,
							'aircraftReg'      => $biddata[0]->registration,
							'aircraftICAO'     => $aircraftinfo->icao,
							'aircraftFullName' => $aircraftinfo->fullname,
							'flightLevel'      => $biddata[0]->flightlevel,
							'aircraftMaxPax'   => $aircraftinfo->maxpax,
							'aircraftCargo'    => $aircraftinfo->maxcargo,
							'depICAO'          => $biddata[0]->depicao,
							'arrICAO'          => $biddata[0]->arricao,
							'route'            => $biddata[0]->route,
							'depTime'          => $biddata[0]->deptime,
							'arrTime'          => $biddata[0]->arrtime,
							'flightTime'       => $biddata[0]->flighttime,
							'flightType'       => $biddata[0]->flighttype,
							'aircraftName'     => $aircraftinfo->name,
							'aircraftRange'    => $aircraftinfo->range,
							'aircraftWeight'   => $aircraftinfo->weight,
							'aircraftCruise'   => $aircraftinfo->cruise
							);
					}			
					else		
					{	
						$params = array('flightStatus' 	   => '2');								
					}
					
					$send = $this->sendXML($params);
					break;			
				
				case 'liveupdate':	
					
					$pilotid = PilotData::parsePilotID($this->xml->verify->pilotID);
					
					# Get the distance remaining
					$depapt = OperationsData::GetAirportInfo($xml->liveupdate->depICAO);
					$arrapt = OperationsData::GetAirportInfo($xml->liveupdate->arrICAO);
					$dist_remain = round(SchedulesData::distanceBetweenPoints(
						$xml->liveupdate->latitude, $xml->liveupdate->longitude, 
						$arrapt->lat, $arrapt->lng));
					
					# Estimate the time remaining
					if($xml->liveupdate->groundSpeed > 0)
					{
						$Minutes = round($dist_remain / $xml->liveupdate->groundSpeed * 60);
						$time_remain = self::ConvertMinutes2Hours($Minutes);
					}
					else
					{
						$time_remain = '00:00';
					}		
					
					$lat = str_replace(",", ".", $xml->liveupdate->latitude);
					$lon = str_replace(",", ".", $xml->liveupdate->longitude);
					
					$fields = array(
						'pilotid'        =>$pilotid,
						'flightnum'      =>$xml->liveupdate->flightNumber,
						'pilotname'      =>'',
						'aircraft'       =>$xml->liveupdate->registration,
						'lat'            =>$lat,
						'lng'            =>$lon,
						'heading'        =>$xml->liveupdate->heading,
						'alt'            =>$xml->liveupdate->altitude,
						'gs'             =>$xml->liveupdate->groundSpeed,
						'depicao'        =>$xml->liveupdate->depICAO,
						'arricao'        =>$xml->liveupdate->arrICAO,
						'deptime'        =>$xml->liveupdate->depTime,
						'arrtime'        =>'',
						'route'          =>$xml->liveupdate->route,
						'distremain'     =>$dist_remain,
						'timeremaining'  =>$time_remain,
						'phasedetail'    =>$xml->liveupdate->status,
						'online'         =>'',
						'client'         =>'kACARS',
						);
					
					$this->log("UpdateFlightData: \n".print_r($fields, true), 'kacars');
					ACARSData::UpdateFlightData($pilotid, $fields);	
					
					break;
				
				case 'pirep':						
					
					$flightinfo = SchedulesData::getProperFlightNum($xml->pirep->flightNumber);
					$code = $flightinfo['code'];
					$flightnum = $flightinfo['flightnum'];
					
					$pilotid = PilotData::parsePilotID($this->xml->verify->pilotID);
					
					# Make sure airports exist:
					#  If not, add them.
					
					if(!OperationsData::GetAirportInfo($xml->pirep->depICAO))
					{
						OperationsData::RetrieveAirportInfo($xml->pirep->depICAO);
					}
					
					if(!OperationsData::GetAirportInfo($xml->pirep->arrICAO))
					{
						OperationsData::RetrieveAirportInfo($xml->pirep->arrICAO);
					}
					
					# Get aircraft information
					$reg = trim($xml->pirep->registration);
					$ac = OperationsData::GetAircraftByReg($reg);
					
					# Load info
					/* If no passengers set, then set it to the cargo */
					$load = $xml->pirep->pax;
					if(empty($load))
						$load = $xml->pirep->cargo;						
					
					/* Fuel conversion - kAcars only reports in lbs */
					$fuelused = $xml->pirep->fuelUsed;
					if(Config::Get('LiquidUnit') == '0')
					{
						# Convert to KGs, divide by density since d = mass * volume
						$fuelused = ($fuelused * .45359237) / .8075;
					}
					# Convert lbs to gallons
					elseif(Config::Get('LiquidUnit') == '1')
					{
						$fuelused = $fuelused * 6.84;
					}
					# Convert lbs to kgs
					elseif(Config::Get('LiquidUnit') == '2')
					{
						$fuelused = $fuelused * .45359237;
					}					
					
					$data = array(
						 'pilotid'=>$pilotid,
						'code'=>$code,
						'flightnum'=>$flightnum,
						'depicao'=>$xml->pirep->depICAO,
						'arricao'=>$xml->pirep->arrICAO,
						'aircraft'=>$ac->id,
						'flighttime'=>$xml->pirep->flightTime,
						'submitdate'=>'NOW()',
						'comment'=>$xml->pirep->comments,
						'fuelused'=>$fuelused,
						'source'=>'kACARS',
						'load'=>$load,
						'landingrate'=>$xml->pirep->landing,
						'log'=>$xml->pirep->log
					);
					
					$this->log("File PIREP: \n".print_r($data, true), 'kacars');
					$ret = ACARSData::FilePIREP($pilotid, $data);		
					
					if ($ret)
					{
						$params = array(
							'pirepStatus' 	   => '1');	// Pirep Filed!							
					}
					else
					{
						$params = array(
							'pirepStatus' 	   => '2');	// Please Try Again!							
						
					}
					$send = $this->sendXML($params);						
					
					break;	
				
				case 'aircraft':
					
					$this->getAllAircraft();
					break;
			}
			
		}
	}
	
	public function ConvertMinutes2Hours($Minutes)
	{
		if ($Minutes < 0)
		{
			$Min = Abs($Minutes);
		}
		else
		{
			$Min = $Minutes;
		}
		$iHours = Floor($Min / 60);
		$Minutes = ($Min - ($iHours * 60)) / 100;
		$tHours = $iHours + $Minutes;
		if ($Minutes < 0)
		{
			$tHours = $tHours * (-1);
		}
		$aHours = explode(".", $tHours);
		$iHours = $aHours[0];
		if (empty($aHours[1]))
		{
			$aHours[1] = "00";
		}
		$Minutes = $aHours[1];
		if (strlen($Minutes) < 2)
		{
			$Minutes = $Minutes ."0";
		}
		$tHours = $iHours .":". $Minutes;
		return $tHours;
	}
	
	/*public function getLatestBid2($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT s.*, b.bidid, a.id as aircraftid, a.name as aircraft, a.registration, a.maxpax, a.maxcargo
				FROM '.TABLE_PREFIX.'schedules s, 
					 '.TABLE_PREFIX.'bids b,
					 '.TABLE_PREFIX.'aircraft a
				WHERE b.routeid = s.id 
					AND s.aircraft=a.id
					AND b.pilotid='.$pilotid.'
				ORDER BY b.bidid ASC LIMIT 1';
		
		return DB::get_row($sql);
	}*/
	
	public function sendXML($params)
	{
		$xml = new SimpleXMLElement("<sitedata />");
		
		$info_xml = $xml->addChild('info');
		foreach($params as $name => $value)
		{
			$info_xml->addChild($name, $value);
		}
		
		header('Content-type: text/xml'); 		
		$xml_string = $xml->asXML();
		echo $xml_string;
		
		# For debug
		$this->log("Sending: \n".print_r($xml_string, true), 'kacars');
		
		return;	
	}
	
	public static function getAllAircraft()
	{
		$results = OperationsData::getAllAircraft(true);
		
		$xml = new SimpleXMLElement("<aircraftdata />");
		
		$info_xml = $xml->addChild('info');
		
		foreach($results as $row)
		{
			$info_xml->addChild('aircraftICAO', $row->icao);
			$info_xml->addChild('aircraftReg', $row->registration);
		}
		
		# For debug
		$this->log("Sending: \n".print_r($xml_string, true), 'kacars');
		
		header('Content-type: text/xml');
		echo $xml->asXML();
	}
	
	public static function ProcessLogin($useridoremail, $password)
	{
		# Allow them to login in any manner:
		#  Email: blah@blah.com
		#  Pilot ID: VMA0001, VMA 001, etc
		#  Just ID: 001
		if(is_numeric($useridoremail))
		{
			$useridoremail =  $useridoremail - intval(Config::Get('PILOTID_OFFSET'));
			$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
				   WHERE pilotid='.$useridoremail;
		}
		else
		{
			if(preg_match('/^.*\@.*$/i', $useridoremail) > 0)
			{
				$emailaddress = DB::escape($useridoremail);
				$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
					   WHERE email=\''.$useridoremail.'\'';
			} 
			
			elseif(preg_match('/^([A-Za-z]*)(.*)(\d*)/', $useridoremail, $matches)>0)
			{
				$id = trim($matches[2]);
				$id = $id - intval(Config::Get('PILOTID_OFFSET'));
				
				$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
					   WHERE pilotid='.$id;
			}
			
			else
			{				
				return false;
			}
		}
		
		$password = DB::escape($password);
		$userinfo = DB::get_row($sql);

		if(!$userinfo)
		{			
			return false;
		}
		
		if($userinfo->retired == 1)
		{			
			return false;
		}

		//ok now check it
		$hash = md5($password . $userinfo->salt);
		
		if($hash == $userinfo->password)
		{						
			return true;
		}			
		else 
		{					
			return false;
		}
	}
}