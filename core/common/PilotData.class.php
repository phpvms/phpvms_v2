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
 
class PilotData
{
	
	public static $pilot_data = array();
	
	/**
	 * Get all the pilots, or the pilots who's last names start
	 * with the letter
	 */
	public static function GetAllPilots($letter='')
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'pilots ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";

		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all the detailed pilot's information
	 */
	public static function GetAllPilotsDetailed($start='', $limit=20)
	{
		$sql = 'SELECT p.*, r.rankimage, r.payrate
					FROM '.TABLE_PREFIX.'pilots p
					LEFT JOIN '.TABLE_PREFIX.'ranks r ON r.rank = p.rank
					ORDER BY totalhours DESC';
		
		if($start!='')
			$sql .= ' LIMIT '.$start.','.$limit;
			
		return DB::get_results($sql);
	}
	
	/**
	 * Get a pilot's avatar
	 */
	public static function GetPilotAvatar($pilotid)
	{
		$pilot = self::GetPilotData($pilotid);
		$link = SITE_URL.'/lib/avatars/'.$pilot->code.$pilot->pilotid.'.'.$pilot->ext;
		return $link;
	}
	
	/**
	 * Get all the pilots on a certain hub
	 */
	public static function GetAllPilotsByHub($hub)
	{
		$sql = "SELECT p.*, r.rankimage, r.payrate
					FROM ".TABLE_PREFIX."pilots p
						INNER JOIN ".TABLE_PREFIX."ranks r ON r.rank=p.rank
					WHERE p.hub='$hub'
					ORDER BY p.pilotid DESC";
					
		return DB::get_results($sql);
	}
	
	/**
	 * Return the pilot's code (ie DVA1031), using
	 * the code and their DB ID
	 */
	public static function GetPilotCode($code, $pilotid)
	{
		# Make sure values are entered
		if(Config::Get('PILOTID_LENGTH') == '')
			Config::Set('PILOTID_LENGTH', 4);
		
		if(Config::Get('PILOTID_OFFSET') == '')
			Config::Set('PILOTID_OFFSET', 0);
			
		$pilotid = $pilotid + Config::Get('PILOTID_OFFSET');
		return $code . str_pad($pilotid, Config::Get('PILOTID_LENGTH'), '0', STR_PAD_LEFT);
	}
	
	/**
	 * The the basic pilot information
	 * Quasi 'cached' in case it's called multiple times
	 * for the same pilot in one script
	 */
	public static function GetPilotData($pilotid)
	{	
		if(!isset(self::$pilot_data[$pilotid]))
		{	
			$sql = "SELECT p.*, r.`rankimage`, r.`payrate`
						FROM ".TABLE_PREFIX."pilots p
							LEFT JOIN ".TABLE_PREFIX."ranks r ON r.`rank`=p.`rank`
						WHERE p.`pilotid`='$pilotid'";
			
			$data = DB::get_row($sql);
						
			if(!is_object($data) || DB::errno() != 0)
			{
				return false;
			}
			
			# "Cache" it
			self::$pilot_data[$pilotid] = $data;
		}
		
		return self::$pilot_data[$pilotid];
	}
	
	/**
	 * Get a pilot's information by email
	 */
	public static function GetPilotByEmail($email)
	{
		$sql = 'SELECT * 
					FROM '. TABLE_PREFIX.'pilots 
					WHERE `email`=\''.$email.'\'';
				
		return DB::get_row($sql);
	}

	/**
	 * Get the list of all the pending pilots
	 */
	public static function GetPendingPilots($count='')
	{
		$sql = 'SELECT * 
					FROM '.TABLE_PREFIX.'pilots WHERE `confirmed`='.PILOT_PENDING;

		if($count!='')
			$sql .= ' LIMIT '.intval($count);

		return DB::get_results($sql);
	}
	
	public static function GetLatestPilots($count=10)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
					ORDER BY `pilotid` DESC
					LIMIT '.$count;
	
		return DB::get_results($sql);
	}
	
	/**
	 * Change a pilot's name. This is separate because this is an
	 *  admin-only operation (strictly speaking), and isn't included
	 *  in a normal change of a pilot's profile (whereas SaveProfile
	 *  only changes minute information
	 */
	 
	public static function ChangeName($pilotid, $firstname, $lastname)
	{
		# Non-blank
		if($pilotid=='' || $firstname == '' || $lastname == '')
		{
			return false;
		}			
		
		# Clean up for DB
		$firstname = DB::escape($firstname);
		$lastname = DB::escape($lastname);
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pilots 
					SET `firstname`=\''.$firstname.'\', `lastname`=\''.$lastname.'\'
					WHERE `pilotid`='.intval($pilotid);
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	/**
	 * Save the email and location changes to the pilot's prfile
	 */
	public static function SaveProfile($pilotid, $email, $location, $hub='', $bgimage='', $retired=false)
	{
		
		unset(self::$pilot_data[$pilotid]);
		
		$location = strtolower($location);
		
		$sql = "UPDATE ".TABLE_PREFIX."pilots 
					SET `email`='$email', `location`='$location' ";
		
		if($hub!= '')
			$sql.=", `hub`='$hub' ";
			
		if($bgimage != '')
			$sql.=",`bgimage`='$bgimage' ";
		
		if($retired === true)
			$retired = 1;
		else
			$retired = 0;
		
		$sql.=", `retired`=$retired ";
			
		$sql .= 'WHERE `pilotid`='.$pilotid;
		
		$res = DB::query($sql);
					
		if(DB::errno() != 0)
		{
			return false;
		}
			
		# Generate a fresh signature
		self::GenerateSignature($pilotid);
		
		return true;
	}
	
	
	/**
	 * Returns an array with a list of background images available
	 *
	 * @return array The background images list
	 *
	 */
	public static function GetBackgroundImages()
	{
		$list = array();
		$files = scandir(SITE_ROOT.'/lib/signatures/background');

		foreach($files as $file)
		{   
			if($file == '.' || $file == '..') continue;
			
    		if(strstr($file, '.png') !== false)
				$list[] = $file;
		}
		
		return $list;
	}
	
	/**
	 * Save avatars
	 */
	public static function SaveAvatar($code, $pilotid, $_FILES)
	{
		# Check the proper file size
		#  Ignored for now since there is a resize
		/*if ($_FILES['avatar']['size'] > Config::Get('AVATAR_FILE_SIZE'))
		{
			return false;
		}*/
		
		if(!$_FILES['avatar']['type'])
			return false;
			
		# Create the image so we can convert it to PNG
		if($_FILES['avatar']['type'] == 'image/gif')
		{
			$img = imagecreatefromgif($_FILES['file']['tmp_name']);
		}
		elseif($_FILES['avatar']['type'] == 'image/jpeg' 
				|| $_FILES['avatar']['type'] == 'image/pjpeg')
		{
			$img = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
		}
		elseif($_FILES['avatar']['type'] == 'image/png')
		{
			$img = imagecreatefrompng($_FILES['avatar']['tmp_name']);
		}
		
		# Resize it
		$height = imagesy($img);
		$width = imagesx($img);
		
		$new_width = Config::Get('AVATAR_MAX_WIDTH');
		$new_height = floor( $height * ( Config::Get('AVATAR_MAX_HEIGHT') / $width ) );
		
		$avatarimg = imagecreatetruecolor($new_width, $new_height);
		imagecopyresized($avatarimg, $img, 0,0,0,0,$new_width, $new_height, $width, $height);
		
		# Output the file, to /lib/avatar/pilotcode.png
		$pilotCode = self::GetPilotCode($code, $pilotid);
		imagepng($avatarimg, SITE_ROOT.AVATAR_PATH.'/'.$pilotCode.'.png');
		imagedestroy($img);
	}
	
	/**
	 * Accept the pilot (allow them into the system)
	 */
	public static function AcceptPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots 
					SET `confirmed`='.PILOT_ACCEPTED.'
					WHERE `pilotid`='.$pilotid;
					
		DB::query($sql);
	}
	
	/**
	 * Reject a pilot
	 */
   	public static function RejectPilot($pilotid)
	{
		/*$sql = 'UPDATE ' . TABLE_PREFIX.'pilots 
					SET confirmed='.PILOT_REJECTED.'
					WHERE pilotid='.$pilotid;*/
		
		return self::DeletePilot($pilotid);
	}
	
		
	/**
	 * Completely delete a pilot	
	 *
	 * @param int $pilotid Pilot ID
	 * @return mixed This is the return value description
	 *
	 */
	public static function DeletePilot($pilotid)
	{
		$sql = array();
		unset(self::$pilot_data[$pilotid]);
	
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'acarsdata WHERE pilotid='.$pilotid;
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'bids WHERE pilotid='.$pilotid;
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'pireps WHERE pilotid='.$pilotid;
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'pilots WHERE pilotid='.$pilotid;
		
		# These SHOULD delete on cascade
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'fieldvalues WHERE pilotid='.$pilotid;
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'groupmembers WHERE pilotid='.$pilotid;
		$sql[] = 'DELETE FROM '.TABLE_PREFIX.'pirepcomments WHERE pilotid='.$pilotid;
		
		foreach($sql as $query)
		{
			$res = DB::query($query);
		}
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	/**
	 * Update the login time
	 */
	public static function UpdateLogin($pilotid)
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots 
					SET lastlogin=NOW() 
					WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
		
	/**
	 * Get the total number of hours for a pilot, add them up
	 *
	 * @param int $pilotid The pilot ID
	 * @return int Total hours for pilot
	 *
	 */
	public static function UpdateFlightHours($pilotid)
	{
		$pireps = PIREPData::GetAllReportsForPilot($pilotid);
		$total = 0;
		
		$allflights = count($pireps);
		if($allflights != 0)
		{
			foreach($pireps as $report)
			{
				if($report->accepted != PIREP_ACCEPTED)
					continue;
					
				$total = Util::AddTime($total, $report->flighttime);
			}
		}
		
		$sql = "UPDATE " .TABLE_PREFIX."pilots
					SET totalhours=$total, totalflights=$allflights
					WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		return $total;
	}
		
	/**
	 * Update a pilot's flight data, ie after a pirep
	 *
	 * @param int $pilotid Pilot ID
	 * @param int $flighttime Number of hours.minutes to increment by
	 * @param int $numflights Number of flights (default 1)
	 * @return bool Success
	 *
	 */
	public static function UpdateFlightData($pilotid, $flighttime, $numflights=1)
	{	
		
		# Update the flighttime
		$pilotdata = PilotData::GetPilotData($pilotid);		
		$flighttime = Util::AddTime($pilotdata->totalhours, $flighttime);
		
		if($numflights == '')
			$numflights = 1;
	
		$sql = "UPDATE " .TABLE_PREFIX."pilots
					SET totalhours=$flighttime,
						totalflights=totalflights+$numflights
					WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	/**
	 * Update the last PIREP date for a pilot
	 *
	 * @param int $pilotid Pilot ID
	 * @return bool Success
	 *
	 */
	public static function UpdateLastPIREPDate($pilotid)
	{
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pilots
					SET `lastpirep`=NOW()
					WHERE pilotid='.$pilotid;
		
		DB::query($sql);
				
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	/**
	 * Don't update the pilot's flight data, but just replace it
	 * 	with the values given
	 *
	 * @param int $pilotid Pilot ID
	 * @param int $flighttime Number of flight hours
	 * @param int $numflights Number of flights
	 * @param int $totalpay The total amount of money they have
	 * @return bool Success
	 *
	 */
	public static function ReplaceFlightData($pilotid, $flighttime, $numflights, $totalpay, $transferhours='')
	{
		$pilotid = intval($pilotid);
		$flighttime = floatval($flighttime);
		$numflights = floatval($numflights);
		$totalpay = floatval($totalpay);
		$transferhours = floatval($transferhours);
		
		$sql = "UPDATE " .TABLE_PREFIX."pilots
					SET totalhours=$flighttime, totalflights=$numflights, totalpay=$totalpay";
		
		if($transferhours != '')
		{
			$sql .=", transferhours=$transferhours";	
		}	
					
		$sql .= " WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	/**
	 * Get the total pay for a flight at a certain rate, 
	 *	for a certain number of hours
	 *
	 * @param float $hours Number of hours in Hours.Minutes format
	 * @param float $rate Hourly rate
	 * @return float Returns the total
	 *
	 */
	public static function get_pilot_pay($hours, $rate)
	{
		/* Hours are in hours.minutes
			convert to minutes */
		$peices = explode('.', $hours);
		$minutes = ($peices[0] * 60) + $peices[1];
		$payupdate = $minutes * ($rate/60);
		
		return $payupdate;
	}
		
	/**
	 * Update a pilot's pay. Pass the pilot ID, and the number of
	 * hours they are being paid for
	 *
	 * @param int $pilotid The pilot ID
	 * @param int $flighthours Number of hours to pay the pilot for
	 * @return bool Success
	 *
	 */
	public static function UpdatePilotPay($pilotid, $flighthours)
	{
		$sql = 'SELECT payrate 
					FROM '.TABLE_PREFIX.'ranks r, '.TABLE_PREFIX.'pilots p 
					WHERE p.rank=r.rank 
						AND p.pilotid='.$pilotid;
						
		$payrate = DB::get_row($sql);
		
		$payupdate = self::get_pilot_pay($flighthours, $payrate->payrate);
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pilots 
					SET totalpay=totalpay+'.$payupdate.'
					WHERE pilotid='.$pilotid;
					
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
		
	}
	
	
	/**
	 * This saves all of the custom fields attributed to pilot
	 * Pass an associated array (fieldname NOT title) to value
	 *
	 * @param int $pilotid Pilot ID
	 * @param array $list fieldname=>fieldvalue associated array
	 * @return bool Success value
	 *
	 */
	public static function SaveFields($pilotid, $list)
	{
		$allfields = RegistrationData::GetCustomFields(true);
		
		if(!$allfields)
			return true;
			
		foreach($allfields as $field)
		{
			$sql = 'SELECT id FROM '.TABLE_PREFIX.'fieldvalues 
						WHERE fieldid='.$field->fieldid.' 
							AND pilotid='.$pilotid;
							
			$res = DB::get_row($sql);

			$fieldname =str_replace(' ', '_', $field->fieldname);
			
			if(!isset($list[$fieldname]))
				continue;
				
			$value = $list[$fieldname];
				
			// if it exists
			if($res)
			{
				$sql = 'UPDATE '.TABLE_PREFIX.'fieldvalues
							SET value="'.$value.'" 
							WHERE fieldid='.$field->fieldid.' AND pilotid='.$pilotid;
			}
			else
			{
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues
						(fieldid, pilotid, value) VALUES ($field->fieldid, $pilotid, '$value')";
			}
			
			DB::query($sql);
		}
		
		return true;
	}
		
	/**
	 * Get all of the custom fields and values for a pilot
	 *
	 * @param int $pilotid The pilot ID
	 * @param bool $inclprivate TRUE to also include private fields (default false)
	 * @return array Returns all of the fields (names and values)
	 *
	 */
	public static function GetFieldData($pilotid, $inclprivate=false)
	{
		$sql = 'SELECT f.title, f.fieldname, v.value, f.public
					FROM '.TABLE_PREFIX.'customfields f
					LEFT JOIN '.TABLE_PREFIX.'fieldvalues v
						ON f.fieldid=v.fieldid
						AND v.pilotid='.$pilotid;
								
		if($inclprivate == false)
			$sql .= ' WHERE f.public=1 ';
			
		return DB::get_results($sql);
	}
	
	
	/**
	 * Get the value of a "custom field" for a pilot
	 *
	 * @param int $pilotid The pilot ID
	 * @param string $title Full title of field, as enter "VATSIM ID"
	 * @return string Returns the value of that field
	 *
	 */
	public static function GetFieldValue($pilotid, $title)
	{
		$sql = "SELECT v.value 
					FROM phpvms_customfields f, phpvms_fieldvalues v 
					WHERE f.fieldid=v.fieldid 
						AND f.title='$title' 
						AND v.pilotid=$pilotid";
						
		$res = DB::get_row($sql);
		return $res->value;
	}
	
	
	/**
	 * Get all of the groups a pilot is a member of
	 *
	 * @param int $pilotid The pilot ID
	 * @return array Groups the pilot is in (groupid and groupname)
	 *
	 */
	public static function GetPilotGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u,'.TABLE_PREFIX.'groups g
					WHERE u.pilotid='.$pilotid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	/**
	 * This generates the forum signature of a pilot which
	 *  can be used wherever. It's dynamic, and adjusts it's
	 *  size, etc based on the background image.
	 * 
	 * Each image is output into the /lib/signatures directory,
	 *  and is named by the pilot code+number (ie, VMA0001.png)
	 * 
	 * This is called whenever a PIREP is accepted by an admin,
	 *  as not to burden a server with image generation
	 * 
	 * Also requires GD to be installed on the server
	 * 
	 * @param int The pilot ID for which to generate a signature for
	 * @return bool Success
	 */	 
	public function GenerateSignature($pilotid)
	{
		$pilot = self::GetPilotData($pilotid);
		$pilotcode = self::GetPilotCode($pilot->code, $pilot->pilotid);
		
		if(Config::Get('TRANSFER_HOURS_IN_RANKS') == true)
			$totalhours = intval($pilot->totalhours) + intval($pilot->transferhours);
		else
			$totalhours = intval($pilot->totalhours);
		
		# Configure what we want to show on each line
		$output = array();
		$output[] = $pilotcode.' '. $pilot->firstname.' '.$pilot->lastname;
		$output[] = $pilot->rank.', '.$pilot->hub;
		$output[] = 'Total Flights: ' . $pilot->totalflights;
		$output[] = 'Total Hours: ' . $pilot->totalhours;
		
		if(Config::Get('SIGNATURE_SHOW_EARNINGS') == true)
		{
			$output[] = 'Total Earnings: $' . $pilot->totalpay;
		}
		
		# Load up our image
		# Get the background image the pilot selected
		if($pilot->bgimage == '')
			$bgimage = SITE_ROOT.'/lib/signatures/background/background.png';
		else
			$bgimage = SITE_ROOT.'/lib/signatures/background/'.$pilot->bgimage;
		
		if(!file_exists($bgimage))
		{
			# Doesn't exist so use the default
			$bgimage = SITE_ROOT.'/lib/signatures/background/background.png';
			
			if(!file_exists($bgimage))
			{
				return false;
			}
		}
		
		$img = @imagecreatefrompng($bgimage);
		if(!$img)
		{
			$img = imagecreatetruecolor(300, 50);
		}
		
		$height = imagesy($img);
		$width = imagesx($img);
			
		$txtcolor = str_replace('#', '',  Config::Get('SIGNATURE_TEXT_COLOR'));
		$color = sscanf($txtcolor, '%2x%2x%2x');
		$textcolor = imagecolorallocate($img, $color[0], $color[1], $color[2]);
		$font = 3; // Set the font-size
		
		$xoffset = 10; # How many pixels, from left, to start
		$yoffset = 10; # How many pixels, from top, to start
		
		# The line height of each item to fit nicely, dynamic
		$stepsize = imagefontheight($font);
		$fontwidth = imagefontwidth($font);
		
		imageantialias($img, true);
		
		$currline = $yoffset;
		foreach($output as $line)
		{
			imagestring($img, $font, $xoffset, $currline, $line, $textcolor);
			$currline+=$stepsize;
		}
		
		# Add the country flag, line it up with the first line, which is the
		#	pilot code/name
		$country = strtolower($pilot->location);
		if(file_exists(SITE_ROOT.'/lib/images/countries/'.$country.'.png'))
		{
			$flagimg = imagecreatefrompng(SITE_ROOT.'/lib/images/countries/'.$country.'.png');
			$ret = imagecopy($img, $flagimg, strlen($output[0])*$fontwidth+20, 
							($yoffset+($stepsize/2)-5.5), 0, 0, 16, 11);
		}
							
		# Add the Rank image
		if(Config::Get('SIGNATURE_SHOW_RANK_IMAGE') == true && $pilot->rankimage!=''
				&& file_exists($pilot->rankimage))
		{
			$ext = substr($pilot->rankimage, strlen($pilot->rankimage)-3, 3);
		
			# Get the rank image type, just jpg, gif or png
			if($ext == 'png')
				$rankimg = @imagecreatefrompng($pilot->rankimage);
			elseif($ext == 'gif')
				$rankimg = @imagecreatefromgif($pilot->rankimage);
			else	
				$rankimg = @imagecreatefromjpg($pilot->rankimage);
				
			if(!$rankimg) { echo '';}
			else 
			{		
				$r_width = imagesx($rankimg);
				$r_height = imagesy($rankimg);
				
				imagecopy($img, $rankimg, $width-$r_width-$xoffset, $yoffset, 0, 0, $r_width, $r_height);
			}
		}	
		
		if(Config::Get('SIGNATURE_SHOW_COPYRIGHT') == true)
		{
			#
			#  DO NOT remove this, as per the phpVMS license
			$font = 1;
			$text = 'powered by phpvms, '. SITE_NAME.' ';
			imagestring($img, $font, $width-(strlen($text)*imagefontwidth($font)), 
						$height-imagefontheight($font), $text, $textcolor);
		}
		
		imagepng($img, SITE_ROOT.SIGNATURE_PATH.'/'.$pilotcode.'.png', 1);
		imagedestroy($img);
	}
}