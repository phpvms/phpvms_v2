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
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class PIREPData
{	
	public static $lasterror;
	public $pirepid;
	
	/**
	 * Return all of the pilot reports. Can pass a start and
	 * count for pagination. Returns 20 rows by default. If you
	 * only want to return the latest n number of reports, use
	 * GetRecentReportsByCount()
	 */
	public static function GetAllReports($start=0, $count=20)
	{
		$sql = 'SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate, 
						u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong						
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						LEFT JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = p.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = p.arricao
					WHERE p.pilotid=u.pilotid
					ORDER BY p.pirepid DESC';
					
		if($start !='' && $count != '')
		{
			$sql .= ' LIMIT '.$start.', '.$count;
		}

		return DB::get_results($sql);
		DB::debug();
		return $ret;
	}
	
	/**
	 * Get all of the reports by the accepted status. Use the
	 * constants:
	 * PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED,PIREP_INPROGRESS
	 */
	public static function GetAllReportsByAccept($accept=0)
	{
		$sql = 'SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate, 
						u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						LEFT JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid AND p.accepted='.$accept;

		return DB::get_results($sql);
	}
	
	public static function GetAllReportsFromHub($accept=0, $hub)
	{
		$sql = "SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate,
						u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						a.name as aircraft, a.registration
					FROM ".TABLE_PREFIX."pilots u, ".TABLE_PREFIX."pireps p
						INNER JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid AND p.accepted=$accept
						AND u.hub='$hub'";

		return DB::get_results($sql);
	}

	/**
	 * Get the latest reports that have been submitted,
	 * return the last 10 by default
	 */
	public static function GetRecentReportsByCount($count = 10)
	{
		if($count == '') $count = 10;

		$sql = 'SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate,
					   u.pilotid, u.firstname, u.lastname, u.email, u.rank,
					   a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						INNER JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid
					ORDER BY p.submitdate DESC
					LIMIT '.intval($count);

		return DB::get_results($sql);
	}

	/**
	 * Get the latest reports by n number of days
	 */
	public static function GetRecentReports($days=2)
	{
		$sql = 'SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate,
					   u.pilotid, u.firstname, u.lastname, u.email, u.rank,
					   a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						INNER JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid
						AND DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY) <= p.submitdate
					ORDER BY p.submitdate DESC';

		return DB::get_results($sql);
	}

	/**
	 * Get the number of reports on a certain date
	 *  Pass unix timestamp for the date
	 */
	public static function GetReportCount($date)
	{
		$sql = 'SELECT COUNT(*) AS count FROM '.TABLE_PREFIX.'pireps
					WHERE DATE(submitdate)=DATE(FROM_UNIXTIME('.$date.'))';

		$row = DB::get_row($sql);
		return $row->count;
	}
	
	/**
	 * Get the number of reports on a certain date, for a certain route
	 */
	public static function GetReportCountForRoute($code, $flightnum, $date)
	{
		$MonthYear = date('mY', $date);
		$sql = "SELECT COUNT(*) AS count FROM ".TABLE_PREFIX."pireps
					WHERE DATE_FORMAT(submitdate, '%c%Y') = '$MonthYear'
						AND code='$code' AND flightnum='$flightnum'";

		$row = DB::get_row($sql);
		return $row->count;
	}

	/**
	 * Get the number of reports for the last x  number of days
	 * Returns 1 row for every day, with the total number of
	 * reports per day
	 */
	public static function GetCountsForDays($days = 7)
	{
		$sql = 'SELECT DISTINCT(DATE(submitdate)) AS submitdate,
					(SELECT COUNT(*) FROM '.TABLE_PREFIX.'pireps WHERE DATE(submitdate)=DATE(p.submitdate)) AS count
				FROM '.TABLE_PREFIX.'pireps p WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= p.submitdate';

		return DB::get_results($sql);
	}

	/**
	 * Get all of the reports for a pilot. Pass the pilot id
	 * The ID is their database ID number, not their airline ID number
	 */
	public static function GetAllReportsForPilot($pilotid)
	{
		/*$sql = 'SELECT pirepid, pilotid, code, flightnum, depicao, arricao, aircraft,
					   flighttime, distance, UNIX_TIMESTAMP(submitdate) as submitdate, accepted
					FROM '.TABLE_PREFIX.'pireps';*/
		$sql = 'SELECT p.pirepid, u.firstname, u.lastname, u.email, u.rank,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong,
					    p.code, p.flightnum, p.depicao, p.arricao, 
						a.id as aircraftid, a.name as aircraft, a.registration, p.flighttime,
					   p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted, p.log
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = p.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = p.arricao
						INNER JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid AND p.pilotid='.intval($pilotid).'
					ORDER BY p.submitdate DESC';

		return DB::get_results($sql);
	}

	/**
	 * Change the status of a PIREP. For the status, use the
	 * constants:
	 * PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED,PIREP_INPROGRESS
	 */
	public static function ChangePIREPStatus($pirepid, $status)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'pireps
					SET accepted='.$status.' WHERE pirepid='.$pirepid;

		return DB::query($sql);
	}

	/**
	 * Get all of the details for a PIREP, including lat/long of the airports
	 */
	public static function GetReportDetails($pirepid)
	{
		$sql = 'SELECT p.*, u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong,
					    p.code, p.flightnum, p.depicao, p.arricao, 
					    a.id as aircraftid, a.name as aircraft, a.registration, p.flighttime,
					    p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted, p.log
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = p.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = p.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft a ON a.id = p.aircraft
					WHERE p.pilotid=u.pilotid AND p.pirepid='.$pirepid;

		return DB::get_row($sql);
	}

	/**
	 * Get the latest reports for a pilot
	 */
	public static function GetLastReports($pilotid, $count = 1, $status='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps
					WHERE pilotid='.intval($pilotid);
					
		# Check it via the status
		if($status != '')
		{
			$sql .= ' AND accepted='.$status;
		}
		
		$sql .=' ORDER BY submitdate DESC
					LIMIT '.intval($count);

		if($count == 1)
			return DB::get_row($sql);
		else
			return DB::get_results($sql);
	}

	/**
	 * Get a pilot's reports by the status.  Use the
	 * constants:
	 * PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED, PIREP_INPROGRESS
	 */
	public static function GetReportsByAcceptStatus($pilotid, $accept=0)
	{

		$sql = 'SELECT * 
					FROM '.TABLE_PREFIX.'pireps
					WHERE pilotid='.intval($pilotid).' 
						AND accepted='.intval($accept);

		return DB::get_results($sql);
	}
	
	/**
	 * Get the count of comments
	 */
	 
	public static function getCommentCount($pirepid)
	{
		
		$sql = 'SELECT COUNT(*) AS total FROM '.TABLE_PREFIX.'pirepcomments
					WHERE pirepid='.$pirepid.'
					GROUP BY pirepid';
		
		$total = DB::get_row($sql)->total;
		
		if($total == '')
			return 0;
		
		return $total;
	}
	

	/**
	 * Get all of the comments for a pilot report
	 */
	public static function GetComments($pirepid)
	{
		$sql = 'SELECT c.comment, UNIX_TIMESTAMP(c.postdate) as postdate,
						p.firstname, p.lastname
					FROM '.TABLE_PREFIX.'pirepcomments c, '.TABLE_PREFIX.'pilots p
					WHERE p.pilotid=c.pilotid AND c.pirepid='.$pirepid.'
					ORDER BY postdate ASC';

		return DB::get_results($sql);
	}
	
	/**
	 * File a PIREP
	 */
	public static function FileReport($pirepdata)
	{
		
		/*$pirepdata = array('pilotid'=>'',
					  'code'=>'',
					  'flightnum'=>'',
					  'leg'=>'',
					  'depicao'=>'',
					  'arricao'=>'',
					  'aircraft'=>'',
					  'flighttime'=>'',
					  'submitdate'=>'',
					  'comment'=>'',
					  'fuelused'=>'',
					  'source'=>''
					  'log'=>'');*/
					  
		if(!is_array($pirepdata))
			return false;
	
		$pirepdata['log'] = DB::escape($pirepdata['log']);
		
		if($pirepdata['depicao'] == '' || $pirepdata['arricao'] == '')
		{
			return false;
		}
		
		# Look up the schedule
		$sched = SchedulesData::GetScheduleByFlight($pirep->code, $pirep->flightnum, $pirep->leg);
		
		# Check the load, if it's blank then
		#	look it up
		if($pirepdata['load'] == '')
		{
			$pirepdata['load'] = FinanceData::GetLoadCount($sched->maxload, $sched->flighttype);
		}
		
		# Get the fuelprice
		if($pirepdata['fuelprice'] == '')
		{
			$pirepdata['fuelprice'] = FinanceData::GetFuelPrice($pirepdata['fuelused'], $pirepdata['depicao']);
		}
		
		# Escape the comment field
		$comment = DB::escape($pirepdata['comment']);
				
		$sql = "INSERT INTO ".TABLE_PREFIX."pireps(	
							`pilotid`, 
							`code`, 
							`flightnum`, 
							`depicao`, 
							`arricao`, 
							`aircraft`, 
							`flighttime`, 
							`submitdate`, 
							`accepted`, 
							`log`,
							`load`,
							`fuelused`,
							`fuelprice`,
							`source`)
					VALUES ($pirepdata[pilotid], 
							'$pirepdata[code]', 
							'$pirepdata[flightnum]', 
							'$pirepdata[depicao]', 
							'$pirepdata[arricao]', 
							'$pirepdata[aircraft]', 
							'$pirepdata[flighttime]', 
							NOW(), 
							".PIREP_PENDING.", 
							'$pirepdata[log]',
							'$pirepdata[load]',
							'$pirepdata[fuelused]',
							'$pirepdata[fuelprice]',
							'$pirepdata[source]')";

		$ret = DB::query($sql);
		$pirepid = DB::$insert_id;
		
		// Add the comment if its not blank
		if($comment != '')
		{
			$pirepid = DB::$insert_id;
			$sql = "INSERT INTO ".TABLE_PREFIX."pirepcomments 
								(`pirepid`, 
								`pilotid`, 
								`comment`, 
								`postdate`)
						VALUES ($pirepid,
								$pirepdata[pilotid], 
								'$pirepdata[comment]', 
								NOW())";

			$ret = DB::query($sql);
		}
		
		
		# Update the financial information for the PIREP:
		
		self::PopulatePIREPFinance($pirepid);
		
		# Do other assorted tasks that are along with a PIREP filing
		# Update the flown count for that route
		self::UpdatePIREPFeed();
		
		# Send an email to the admin that a PIREP was submitted
		$sub = 'PIREP Submitted';
		$message = 'A PIREP has been submitted';
		Util::SendEmail(ADMIN_EMAIL, $sub, $message);
		SchedulesData::IncrementFlownCount($pirepdata['code'], $pirepdata['flightnum']);
		
		DB::$insert_id = $pirepid;
		
		return true;
	}
		
	public static function UpdateFlightReport($pirepid, $pirepdata)
	{		
		/*$pirepdata = array('pilotid'=>'',
					  'code'=>'',
					  'flightnum'=>'',
					  'leg'=>'',
					  'depicao'=>'',
					  'arricao'=>'',
					  'aircraft'=>'',
					  'flighttime'=>'',
					  'submitdate'=>'',
					  'comment'=>'',
					  'log'=>'',
					  'load'=>'');
		*/
		
		if(!is_array($pirepdata))
			return false;
	
		if($pirepdata['depicao'] == '' || $pirepdata['arricao'] == '')
		{
			return false;
		}
		
		# Get the fuelprice
		if($pirepdata['fuelprice'] == '')
		{
			$pirepdata['fuelprice'] = FinanceData::GetFuelPrice();
		}
				
		$sql = "UPDATE ".TABLE_PREFIX."pireps 
				SET `code`='$pirepdata[code]', 
					`flightnum`='$pirepdata[flightnum]',	
					`depicao`='$pirepdata[depicao]', 
					`arricao`='$pirepdata[arricao]', 
					`aircraft`='$pirepdata[aircraft]', 
					`flighttime`='$pirepdata[flighttime]',
					`load`='$pirepdata[load]',
					`fuelused`='$pirepdata[fuelused]',
					`fuelprice`='$pirepdata[fuelprice]'
				WHERE `pirepid`=$pirepid";

		$ret = DB::query($sql);
		
		return true;
	}
	
	/**
	 * 
	 * Populate PIREPS which have 0 values for the load/price, etc
	 */
	 
	public static function PopulateEmptyPIREPS()
	{
		//DB::get_results('SELECT * FROM phpvms_pireps');
		//DB::debug();
		
		$sql = 'SELECT  `pirepid`, `pilotid`, `code`, `flightnum`,
						`load`, `price`, `expenses`, `flighttype`, `pilotpay`
					FROM '.TABLE_PREFIX.'pireps';
					
		$results = DB::get_results($sql);
		
		if(!$results)
		{
			return true;
		}
		
		foreach($results as $row)
		{
			self::PopulatePIREPFinance($row);
		}
	
		return true;		
	}
	
	/**
	 * Populate the PIREP with the fianancial info needed
	 *  Pass the PIREPID or the PIREP row
	 */
	
	public static function PopulatePIREPFinance($pirep)
	{
				
		if(!is_object($pirep))
		{
			$pirep = PIREPData::GetReportDetails($pirep);
			if(!$pirep)
			{
				self::$lasterror = 'PIREP does not exist';
				return false;
			}
		}
		
		# Set the PIREP ID
		$pirepid = $pirep->pirepid;
		
		$sched = SchedulesData::GetScheduleByFlight($pirep->code, $pirep->flightnum, '');
		if(!$sched)
		{
			self::$lasterror = 'Schedule does not exist. Please update this manually.';
			return false;
		}
		
		$pilot = PilotData::GetPilotData($pirep->pilotid);
		
		# Get the load factor for this flight
		$load = '';
		if($pirep->load == '' || $pirep->load == 0)
		{
			$load = FinanceData::GetLoadCount($sched->maxload, $sched->flighttype);
		}
		
		# Check the fuel
		if($pirep->fuelused != '')
		{
			$fuelused = FinanceData::GetFuelPrice($pirep->fuelused, $pirep->depicao);
		}
		
		# Get the expenses for a flight
		$total_ex = 0;
		$expense_list = '';
		
		$allexpenses = FinanceData::GetFlightExpenses();
		//DB::debug();
		
		if(!$allexpenses)
		{
			$allexpenses = array();
		}
		else
		{
			# Add up the total amount so we can add it in
			foreach($allexpenses as $ex)
			{
				echo '<pre>';print_r($ex);echo'</pre>';
				$total_ex += $ex->cost;				
			}
			
			# Serialize and package it, so we can store it
			#	with the PIREP
			$expense_list = serialize($allexpenses);
		}
		
		# Update it
		$sql = 'UPDATE '.TABLE_PREFIX."pireps
					SET `price`='$sched->price', 
						`fuelprice`='$fuelused',
						`expenses`=$total_ex,
						`expenselist`='$expense_list',
						`flighttype`='$sched->flighttype', 
						`pilotpay`='$pilot->payrate'";
		
		if($load != '')
			$sql .= ", `load`='$load'";
			
		$sql .= " WHERE `pirepid`=$pirepid";
					
		DB::query($sql);
	}
	
	/**
	 * Update the PIREP distance
	 */
	 
	public static function UpdatePIREPDistance($pirepid, $distance)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'pireps
					SET distance=\''.$distance.'\'
					WHERE pirepid='.$pirepid;
		
		return DB::query($sql);		
	}
	
	/**
	 * Delete a flight report and all of its associated data
	 */
	public static function DeleteFlightReport($pirepid)
	{
		$pirepid = intval($pirepid);
		$pirep_details = self::GetReportDetails($pirepid);
		
		$sql = 'DELETE FROM '. TABLE_PREFIX.'pireps
					WHERE pirepid='.$pirepid;
		
		DB::query($sql);
					
		# Delete any comments and fields
		$sql = 'DELETE FROM '. TABLE_PREFIX.'comments
					WHERE pirepid='.$pirepid;
				
		DB::query($sql);
		
		# Delete any custom field data
		$sql = 'DELETE FROM '. TABLE_PREFIX.'pirepvalues
					WHERE pirepid='.$pirepid;
		
		DB::query($sql);
		
		# Check if this was accepted report
		#	If it was, remove it from that pilot's stats
		if($pirep_details->accepted == PIREP_ACCEPTED)
		{
			PilotData::UpdateFlightData($pirep_details->pilotid, ($pirep_details->flighttime) * -1, -1);
		}
		
		self::UpdatePIREPFeed();
	}
	
	public static function UpdatePIREPFeed()
	{
		# Load PIREP into RSS feed
		$reports = PIREPData::GetRecentReportsByCount(10);
		$rss = new RSSFeed('Latest Pilot Reports', SITE_URL, 'The latest pilot reports');
		
		# Empty the rss file if there are no pireps
		if(!$reports)
		{
			//file_put_contents(LIB_PATH.'/rss/latestpireps.rss', '');
			$reports = array();
			return;
		}
			
		foreach($reports as $report)
		{
			$rss->AddItem('Report #'.$report->pirepid.' - '.$report->depicao.' to '.$report->arricao,
							SITE_URL.'/admin/index.php?admin=viewpending','',
							'Filed by '.PilotData::GetPilotCode($report->code, $report->pilotid) 
							. " ($report->firstname $report->lastname)");
		}
		
		$rss->BuildFeed(LIB_PATH.'/rss/latestpireps.rss');
	}
	
	/** 
	 * Append to a flight report's log
	 */
	 
	public static function AppendToLog($pirepid, $log)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'pireps 
					SET `log` = CONCAT(`log`, \''.$log.'\')
					WHERE `pirepid`='.$pirepid;
					
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;		
	}

	/**
	 * Add a comment to the flight report
	 */
	public static function AddComment($pirepid, $commenter, $comment)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."pirepcomments (pirepid, pilotid, comment, postdate)
					VALUES ($pirepid, $commenter, '$comment', NOW())";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	public static function GetAllFields()
	{
		return DB::get_results('SELECT * FROM '.TABLE_PREFIX.'pirepfields');
	}
	
	/**
	 * Get all of the "cusom fields" for a pirep
	 */
	public static function GetFieldData($pirepid)
	{
		$sql = 'SELECT f.title, f.name, v.value
					FROM '.TABLE_PREFIX.'pirepfields f
					LEFT JOIN '.TABLE_PREFIX.'pirepvalues v
						ON f.fieldid=v.fieldid 
							AND v.pirepid='.intval($pirepid);
					
		return DB::get_results($sql);
	}
	
	public static function GetFieldInfo($id)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pirepfields
					WHERE fieldid='.$id;
		
		return DB::get_row($sql);
	}
	
	public static function GetFieldValue($fieldid, $pirepid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pirepvalues
					WHERE fieldid='.$fieldid.' AND pirepid='.$pirepid;
		
		$ret = DB::get_row($sql);
		return $ret->value;
	}
	/**
	 * Add a custom field to be used in a PIREP
	 */
	public static function AddField($title, $type='', $values='')
	{
		$fieldname = strtoupper(str_replace(' ', '_', $title));
		//$values = DB::escape($values);
		
		if($type == '')
			$type = 'text';
				
		$sql = "INSERT INTO " . TABLE_PREFIX ."pirepfields (title, name, type, options)
					VALUES ('$title', '$fieldname', '$type', '$values')";
	
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit the field
	 */
	public static function EditField($id, $title, $type, $values='')
	{
		$fieldname = strtoupper(str_replace(' ', '_', $title));
		
		$sql = "UPDATE ".TABLE_PREFIX."pirepfields
					SET title='$title',name='$fieldname', type='$type', options='$values'
					WHERE fieldid=$id";
				
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Save PIREP fields
	 */
	public static function SaveFields($pirepid, $list)
	{
		if(!is_array($list) || $pirepid == '')
			return false;
			
		$allfields = self::GetAllFields();
		
		if(!$allfields) return true;
			
		foreach($allfields as $field)
		{
			// See if that value already exists
			$sql = 'SELECT id FROM '.TABLE_PREFIX.'pirepvalues
						WHERE fieldid='.$field->fieldid.' AND pirepid='.$pirepid;
			$res = DB::get_row($sql);

			$fieldname =str_replace(' ', '_', $field->name);
			$value = $list[$fieldname];
			
			if($res)
			{
				$sql = 'UPDATE '.TABLE_PREFIX."pirepvalues
							SET value='$value'
							WHERE fieldid=$field->fieldid
								AND pirepid=$pirepid";
			}
			else
			{		
				$sql = "INSERT INTO ".TABLE_PREFIX."pirepvalues
						(fieldid, pirepid, value)
						VALUES ($field->fieldid, $pirepid, '$value')";
			}
						
			DB::query($sql);
		}
		
		return true;
	}
		
	public static function DeleteField($id)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'pirepfields WHERE fieldid='.$id;

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;

		//TODO: delete all of the field values!
		//$sql = 'DELETE FROM '.TABLE_PREFIX.'
	}

	
	/**
	 * Show the graph of the past week's reports. Outputs the
	 *	image unless $ret == true
	 */
	public static function ShowReportCounts($ret=false)
	{
		// Recent PIREP #'s
		$max = 0;
		$data = array();
		
		# Get the past 7 days
		$time_start = strtotime('-7 days');
		$time_end = time();
	
		do {
			$count = PIREPData::GetReportCount($time_start);
			$data[] = $count;
			
			if($count > $max)
				$max = $count;
				
			$time_start += SECONDS_PER_DAY;
		} while ($time_start < $time_end);
		
		//$data = substr($data, 0, strlen($data)-1);
		
		return $data;
	}
	
}

?>