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
	
		$sql = 'SELECT p.*, r.rankimage 
					FROM '.TABLE_PREFIX.'pilots p, '.TABLE_PREFIX.'ranks r
					WHERE r.rank = p.rank
					ORDER BY totalhours DESC';
		
		if($start!='')
			$sql .= ' LIMIT '.$start.','.$limit;
			
		return DB::get_results($sql);
	}
	
	/**
	 * Get all the pilots on a certain hub
	 */
	public static function GetAllPilotsByHub($hub)
	{
		$sql = "SELECT p.*, r.rankimage FROM ".TABLE_PREFIX."pilots p, ".TABLE_PREFIX."ranks r
					WHERE r.rank = p.rank AND hub='$hub'
					ORDER BY totalhours DESC";
					
		return DB::get_results($sql);
	}
	
	/**
	 * Return the pilot's code (ie DVA1031), using
	 * the code and their DB ID
	 */
	public static function GetPilotCode($code, $pilotid)
	{
		$pilotid = $pilotid + PILOTID_OFFSET;
		return $code . str_pad($pilotid, 4, '0', STR_PAD_LEFT);
	}
	
	/**
	 * The the basic pilot information
	 */
	public static function GetPilotData($pilotid)
	{
		$sql = 'SELECT *, UNIX_TIMESTAMP(lastlogin) as lastlogin
					FROM '.TABLE_PREFIX.'pilots 
					WHERE pilotid='.$pilotid;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get a pilot's information by email
	 */
	public static function GetPilotByEmail($email)
	{
		$sql = 'SELECT * 
				FROM '. TABLE_PREFIX.'pilots 
				WHERE email=\''.$email.'\'';
				
		return DB::get_row($sql);
	}

	/**
	 * Get the list of all the pending pilots
	 */
	public static function GetPendingPilots($count='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots WHERE confirmed='.PILOT_PENDING;

		if($count!='')
			$sql .= ' LIMIT '.intval($count);

		return DB::get_results($sql);
	}
	
	public static function GetLatestPilots($count=10)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
					ORDER BY pilotid DESC
					LIMIT '.$count;
	
		return DB::get_results($sql);
	}
	
	/**
	 * Save the email and location changes to the pilot's prfile
	 */
	public static function SaveProfile($pilotid, $email, $location, $hub='')
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET email='$email', location='$location' ";
		
		if($hub!= '')
			$sql.=", hub='$hub' ";
			
		$sql .= 'WHERE pilotid='.$pilotid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Accept the pilot (allow them into the system)
	 */
	public static function AcceptPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed='.PILOT_ACCEPTED.'
					WHERE pilotid='.$pilotid;
		DB::query($sql);
	}
	
	/**
	 * Reject a pilot
	 */
   	public static function RejectPilot($pilotid)
	{
		/*$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed='.PILOT_REJECTED.'
					WHERE pilotid='.$pilotid;*/
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'pilots WHERE pilotid='.$pilotid;

		$res = DB::query($sql);
		
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
	 * After a PIREP been accepted, update their statistics
	 */
	public static function UpdateFlightData($pilotid, $flighttime, $numflights=1)
	{
		$sql = "UPDATE " .TABLE_PREFIX."pilots
					SET totalhours=totalhours+$flighttime, totalflights=totalflights+$numflights
					WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Don't update the pilot's flight data, but just replace it
	 * 	with the values given
	 */
	public static function ReplaceFlightData($pilotid, $flighttime, $numflights, $totalpay)
	{
		
		$sql = "UPDATE " .TABLE_PREFIX."pilots
					SET totalhours=$flighttime, totalflights=$numflights, totalpay=$totalpay
					WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Update a pilot's pay. Pass the pilot ID, and the number of
	 * hours they are being paid for
	 */
	function UpdatePilotPay($pilotid, $flighthours)
	{
		
		$sql = 'SELECT payrate 
					FROM '.TABLE_PREFIX.'ranks r, '.TABLE_PREFIX.'pilots p 
					WHERE p.rank=r.rank 
						AND p.pilotid='.$pilotid;
						
		$payrate = DB::get_row($sql);
		$payrate = $payrate->payrate;
		
		$payupdate = floatval($flighthours * $payrate);
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pilots 
					SET totalpay=totalpay+'.$payupdate.'
					WHERE pilotid='.$pilotid;
					
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
		
	}
	
	/**
	 * Save the custom fields. Usually just pass the $_POST
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
							SET value="'.$value.'" WHERE fieldid='.$field->fieldid.' AND pilotid='.$pilotid;
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
	 * Get all of the "cusom fields" for a pilot
	 */
	public static function GetFieldData($pilotid, $inclprivate=false)
	{
		$sql = 'SELECT f.title, f.fieldname, v.value
					FROM '.TABLE_PREFIX.'customfields f
					LEFT JOIN '.TABLE_PREFIX.'fieldvalues v
						ON f.fieldid=v.fieldid AND v.pilotid='.$pilotid;
								
		if($inclprivate == false)
			$sql .= ' AND f.public=1';
			
		return DB::get_results($sql);
	}
	
	/**
	 * Get the field value for a pilot
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
	 * Get the groups a pilot is in
	 */
	public static function GetPilotGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
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
	 */
	 
	public function GenerateSignature($pilotid)
	{
		$pilot = self::GetPilotData($pilotid);
		$pilotcode = self::GetPilotCode($pilot->code, $pilot->pilotid);
		
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
		$img = imagecreatefrompng(SITE_ROOT.'/lib/signatures/background.png');
		$height = imagesy($img);
		$width = imagesx($img);
			
		$textcolor = imagecolorallocate($img, 0, 0, 0);
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
		$flagimg = imagecreatefrompng(SITE_ROOT.'/lib/images/countries/'.$country.'.png');
		$ret = imagecopy($img, $flagimg, strlen($output[0])*$fontwidth+20, 
							($yoffset+($stepsize/2)-5.5), 0, 0, 16, 11);
		
		#
		#  DO NOT remove this, as per the phpVMS license
		$font = 1;
		$text = 'powered by phpvms, '. SITE_NAME.' ';
		imagestring($img, $font, $width-(strlen($text)*imagefontwidth($font)), 
					$height-imagefontheight($font), $text, $textcolor);
	
		imagepng($img, SITE_ROOT.'/lib/signatures/'.$pilotcode.'.png', 1);
		imagedestroy($img);
	}
	
	/*public function GenerateSignatureOld($pilotid)
	{
		$pilot = self::GetPilotData($pilotid);
		
		# Configure what we want to show on each line
		$output = array();
		$output[] = $pilot->firstname.' '.$pilot->lastname;
		$output[] = $pilot->rank;
		$output[] = 'Total Flights: ' . $pilot->totalflights;
		
		# Load up our image
		$img = imagecreatefrompng(SITE_ROOT.'/lib/signatures/background.png');
		$height = imagesy($img);
		$width = imagesx($img);
		
		$xoffset = 10; # How many pixels, from left, to start
		$yoffset = 10; # How many pixels, from top, to start
		
		# The line height of each item to fit nicely, dynamic
		$stepsize = ($height - $yoffset) / count($output);
		
		imageantialias($img, true);
		
		$textcolor = imagecolorallocate($img, 0, 0, 0);
		$font = 3;
		
		foreach($output as $line)
		{
			imagestring($img, $font, $xoffset, $yoffset, $line, $textcolor);
			
			$yoffset+=$stepsize;
			
		}
		
		imagepng($img, SITE_ROOT.'/lib/signatures/'.$pilotid.'.png', 1);
		imagedestroy($img);
	}*/
}
?>