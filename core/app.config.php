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
 * They will get over-ridden in an update.
 *
 * So copy-paste and change the setting into your
 *  local.config.php
 *
 * Most of these are in your local.config.php already
 *
 */

define('SIGNATURE_PATH', '/lib/signatures');
define('AVATAR_PATH', '/lib/avatars');

# Page encoding options
Config::Set('PAGE_ENCODING', 'ISO-8859-1');

Config::Set('PAGE_EXT', '.htm');
Config::Set('PILOTID_OFFSET', 0); // Start the Pilot's ID from 1000
Config::Set('PILOTID_LENGTH', 4);
Config::Set('SHOW_LEG_TEXT', true);
Config::Set('UNITS', 'nm'); // Your units: nm, mi or km
Config::Set('LOAD_FACTOR', '72'); 
Config::Set('CARGO_UNITS', 'lbs');
Config::Set('VA_START_DATE', 'October 2008');

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

# Monetary Units
Config::Set('MONEY_UNIT', '$'); # $, €, etc
Config::Set('MONEY_FORMAT', '%(#10n');

# Units settings
#	These are global, also used for FSPAX
Config::Set('WeightUnit', '0');		# 0=Kg 1=lbs
Config::Set('DistanceUnit', '2');   # 0=KM 1= Miles 2=NMiles
Config::Set('SpeedUnit', '1');		# 0=Km/H 1=Kts
Config::Set('AltUnit', '1');		# 0=Meter 1=Feet 
Config::Set('LiquidUnit', '2');		# 0=liter 1=gal 2=kg 3=lbs
Config::Set('WelcomeMessage', 'phpVMS/FSPAX ACARS'); # Welcome Message

# Options for the signature that's generated 
Config::Set('SIGNATURE_TEXT_COLOR', '#FFFFFF');
Config::Set('SIGNATURE_SHOW_EARNINGS', true);
Config::Set('SIGNATURE_SHOW_RANK_IMAGE', true);
Config::Set('SIGNATURE_SHOW_COPYRIGHT', true);

# Avatar information
Config::Set('AVATAR_FILE_SIZE', 50000);
Config::Set('AVATAR_MAX_WIDTH', 80);
Config::Set('AVATAR_MAX_HEIGHT', 80);

# Debug mode is off by default
Config::Set('DEBUG_MODE', true);
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);

/**
 * *******************************************************
 * 
 * Advanced options, don't edit unless you
 * know what you're doing!!
 * 
 * Actually, don't change them, at all. Please.
 * For your sake. And mine. :)
 */

Config::Set('TEMPLATE_USE_CACHE', false);
Config::Set('TEMPLATE_CACHE_EXPIRE', '24');
Config::Set('DBASE_USE_CACHE', false);
Config::Set('CACHE_PATH', SITE_ROOT . '/core/cache');

if(ADMIN_PANEL == true && defined('ADMIN_PANEL'))
{	
	Template::SetTemplatePath(SITE_ROOT.'/admin/templates');
	
	Config::Set('RUN_SINGLE_MODULE', true);
	Config::Set('MODULES_PATH', SITE_ROOT.'/admin/modules');
	Config::Set('DEFAULT_MODULE', 'Dashboard');
	Config::Set('MODULES_AUTOLOAD', true);
	Config::Set('ACTIVE_MODULES', array());
	Config::Set('URL_REWRITE', array('default'=>array('module', 'page')
				));	
}
else 
{	
	Template::SetTemplatePath(SITE_ROOT.'/core/templates');
	
	Config::Set('RUN_SINGLE_MODULE', true);
	Config::Set('MODULES_PATH', SITE_ROOT.'/core/modules');
	Config::Set('DEFAULT_MODULE', 'Frontpage');
	Config::Set('MODULES_AUTOLOAD', true);
	Config::Set('ACTIVE_MODULES', array());
	
	Config::Set('URL_REWRITE', array(	
				'default'=>array('module', 'page'),
				'Downloads'=>array('module', 'id'),
				'Finance'=>array('module', 'page'),
				'Login'=>array('module', 'page', 'redir'),
				'Logout'=>array('module', 'page', 'redir'),
				'Pages'=>array('module', 'page'),
				'PIREPS'=>array('module', 'page', 'id', 'icao'),
				'Pilots'=>array('module', 'page', 'pilotid'),
				'Profile'=>array('module', 'page', 'pilotid'),
				'Schedules'=>array('module', 'page', 'id'),
				'ACARS'=>array('module', 'page', 'action'),
				'XML'=>array('module', 'request')));
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

Config::Set('LIQUID_UNIT_NAMES', array('liter','gal','kg', 'lbs'));

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3);

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

define('LOAD_VARIATION', 5);
define('SECONDS_PER_DAY', 86400);


/*
 * Library Includes (from 3rd Party)
 */

