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

/**
 * DO NOT MODIFY THESE SETTINGS HERE!!
 * They will get over-ridden in an update. These are just defaults *
 * To change, copy-paste and change the line/option/setting into your
 *  local.config.php file
 *
 * Most of these are in your local.config.php already
 * 
 * View the docs for details about these settings
 */
 

# Debug mode is off by default
Config::Set('DEBUG_MODE', false);
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);

# Page encoding options
Config::Set('PAGE_ENCODING', 'UTF-8');

# See more details about these in the docs
Config::Set('PAGE_EXT', '.htm');	# .htm is fine. You can still run PHP
Config::Set('PILOTID_OFFSET', 0);	# What # to start pilot ID's from
Config::Set('PILOTID_LENGTH', 4);	# Length of the Pilot ID
Config::Set('UNITS', 'nm');			# Your units: nm, mi or km
Config::Set('LOAD_FACTOR', '82');	# %age load factor 
Config::Set('CARGO_UNITS', 'lbs');

# If someone places a bid, whether to disable that or not
Config::Set('DISABLE_SCHED_ON_BID', true);

# If you want to count transfer hours in rank calculations
Config::Set('TRANSFER_HOURS_IN_RANKS', false);

# Google Map Options
Config::Set('MAP_WIDTH', '600px');
Config::Set('MAP_HEIGHT', '400px');
Config::Set('MAP_TYPE', 'G_PHYSICAL_MAP');
Config::Set('MAP_LINE_COLOR', '#ff0000');
Config::Set('MAP_CENTER_LAT', '45.484400');
Config::Set('MAP_CENTER_LNG', '-62.334821');

# ACARS options
#  Minutes, flights to show on the ACARS
#  Default is 720 minutes (12 hours)
Config::Set('ACARS_LIVE_TIME', 720); 
Config::Set('ACARS_DEBUG', false);

/*
  This is the unit of money. For non-dollars, use :
	Dollars ($), enter "$"
	Euro (€), enter "&#8364;"
	Yen (¥), enter "&yen;"
	Pounds (£), enter "&pound;"
	
  For example, to set EUROS:
	Config::Set('MONEY_UNIT', '&#8364;');
 */
 
Config::Set('MONEY_UNIT', '$');

/*
 To change the money format, look at:
  http://us3.php.net/money_format
 
 However, I do not recommend changing this
 */
 
Config::Set('MONEY_FORMAT', '%(#10n');


# Fuel info
/* Default fuel price, for airports that don't have
	And the surcharge percentage. View the docs
	for more details about these
*/ 
Config::Set('FUEL_DEFAULT_PRICE', '5.10');
Config::Set('FUEL_SURCHARGE', '5');

# Units settings
#	These are global, also used for FSPAX
Config::Set('WeightUnit', '1');		# 0=Kg 1=lbs
Config::Set('DistanceUnit', '2');   # 0=KM 1= Miles 2=NMiles
Config::Set('SpeedUnit', '1');		# 0=Km/H 1=Kts
Config::Set('AltUnit', '1');		# 0=Meter 1=Feet 
Config::Set('LiquidUnit', '2');		# 0=liter 1=gal 2=kg 3=lbs
Config::Set('WelcomeMessage', 'phpVMS/FSPAX ACARS'); # Welcome Message
Config::Set('LIQUID_UNIT_NAMES', array('liter','gal','kg', 'lbs'));

# Options for the signature that's generated 
Config::Set('SIGNATURE_TEXT_COLOR', '#FFFFFF');
Config::Set('SIGNATURE_SHOW_EARNINGS', true);
Config::Set('SIGNATURE_SHOW_RANK_IMAGE', true);
Config::Set('SIGNATURE_SHOW_COPYRIGHT', true);

# Avatar information
Config::Set('AVATAR_FILE_SIZE', 50000);	# Maximum file-size they can upload
Config::Set('AVATAR_MAX_WIDTH', 80);	# Resized width
Config::Set('AVATAR_MAX_HEIGHT', 80);	# Resized height

Config::Set('PHPVMS_API_SERVER', 'http://api.phpvms.net');

/* Days of the Week
	The compacted view, and the full text
 */

Config::Set('DAYS_COMPACT',  array('Su', 'M', 'T', 'W', 'Th', 'F', 'S', 'Su'));

Config::Set('DAYS_LONG', 
			array('Sunday',
				  'Monday',
				  'Tuesday',
				  'Wednesday',
				  'Thursday',
				  'Friday',
				  'Saturday',
				  'Sunday'
			)
		);
















/**
 * *******************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 * Advanced options, don't edit unless you
 * know what you're doing!!
 * 
 * Actually, don't change them, at all. Please.
 * For your sake. And mine. :)
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

Config::Set('TEMPLATE_USE_CACHE', false);
Config::Set('TEMPLATE_CACHE_EXPIRE', '24');
Config::Set('DBASE_USE_CACHE', false);
Config::Set('CACHE_PATH', SITE_ROOT . '/core/cache');

if(defined('ADMIN_PANEL') && ADMIN_PANEL === true)
{	
	Template::SetTemplatePath(SITE_ROOT.'/admin/templates');
	
	Config::Set('RUN_SINGLE_MODULE', true);
	Config::Set('MODULES_PATH', SITE_ROOT.'/admin/modules');
	Config::Set('DEFAULT_MODULE', 'Dashboard');
	Config::Set('MODULES_AUTOLOAD', true);
	Config::Set('ACTIVE_MODULES', array());
	
	CodonRewrite::AddRule('default', array('page'));
}
else 
{	
	Template::SetTemplatePath(SITE_ROOT.'/core/templates');
	
	Config::Set('RUN_SINGLE_MODULE', true);
	Config::Set('MODULES_PATH', SITE_ROOT.'/core/modules');
	Config::Set('DEFAULT_MODULE', 'Frontpage');
	Config::Set('MODULES_AUTOLOAD', true);
	Config::Set('ACTIVE_MODULES', array());
	
	/* Rules for the controllers */
	CodonRewrite::AddRule('default', array('page'));
	CodonRewrite::AddRule('acars', array('page', 'action'));
	CodonRewrite::AddRule('downloads', array('id'));
	CodonRewrite::AddRule('login', array('page', 'redir'));
	CodonRewrite::AddRule('logout', array('page', 'redir'));
	CodonRewrite::AddRule('pireps', array('page', 'id', 'icao'));
	CodonRewrite::AddRule('pilots', array('page', 'pilotid'));
	CodonRewrite::AddRule('profile', array('page', 'pilotid'));
	CodonRewrite::AddRule('schedules', array('page', 'id'));
	CodonRewrite::AddRule('xml', array('request'));
}

/**
 * Constants
 *	Do not modify these! All sorts of weird shit can happen
 */ 
# Set the type of flights we have
Config::Set('FLIGHT_TYPES', array(	'P'=>'Passenger',
			'C'=>'Cargo',
			'H'=>'Charter'));
			
# Set the types of expenses we have
Config::Set('EXPENSE_TYPES', array( 'M'=>'Monthly',
			'F'=>'Per Flight'));
			
define('SIGNATURE_PATH', '/lib/signatures');
define('AVATAR_PATH', '/lib/avatars');

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3);

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

define('LOAD_VARIATION', 5);
define('SECONDS_PER_DAY', 86400);

define('GEONAME_URL', 'http://ws.geonames.org');

/**
 * Library Includes (from 3rd Party)
 */

# PHPMailer
include_once(SITE_ROOT.'/core/lib/phpmailer/class.phpmailer.php');

# Bit-masks for permission sets
$permission_set = array
	 (/*'NO_ADMIN_ACCESS'			=> 0,*/
	  'EDIT_NEWS'				=> 0x1, 
	  'EDIT_PAGES'				=> 0x2, 
	  'EDIT_DOWNLOADS'			=> 0x4,
	  'EMAIL_PILOTS'			=> 0x8, 
	  'EDIT_AIRLINES'			=> 0x10,
	  'EDIT_FLEET'				=> 0x20,
	  'EDIT_SCHEDULES'			=> 0x80,
	  'IMPORT_SCHEDULES'		=> 0x100,
	  'MODERATE_REGISTRATIONS'	=> 0x200,
	  'EDIT_PILOTS'				=> 0x400,
	  'EDIT_GROUPS'				=> 0x800,
	  'EDIT_RANKS'				=> 0x1000,
	  'EDIT_AWARDS'				=> 0x2000,
	  'MODERATE_PIREPS'			=> 0x4000,
	  'VIEW_FINANCES'			=> 0x8000,
	  'EDIT_EXPENSES'			=> 0x10000,
	  'EDIT_SETTINGS'			=> 0x20000,
	  'EDIT_PIREPS_FIELDS'		=> 0x40000,
	  'EDIT_PROFILE_FIELDS'		=> 0x80000,
	  'EDIT_VACENTRAL'			=> 0x100000,
	  'ACCESS_ADMIN'			=> 0x2000000,
	  'FULL_ADMIN'				=> 35651519);

Config::Set('permission_set', $permission_set);  
foreach($permission_set as $key=>$value)
{
	define($key, $value);
}