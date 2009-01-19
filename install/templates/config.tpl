<?php

/**
 * This is the phpVMS Main Configuration File
 *
 * You can add any additional modules at the bottom
 * This file won't be modified/touched by future versions
 * of phpVMS, you can change your settings here
 *
 */
define('DBASE_USER', '$DBASE_USER');
define('DBASE_PASS', '$DBASE_PASS');
define('DBASE_NAME', '$DBASE_NAME');
define('DBASE_SERVER', '$DBASE_SERVER');
define('DBASE_TYPE', '$DBASE_TYPE');

define('TABLE_PREFIX', '$TABLE_PREFIX');

define('SITE_URL', 'http://$SITE_URL');

# Page encoding options
Config::Set('PAGE_ENCODING', 'ISO-8859-1');

Config::Set('PILOTID_OFFSET', 0); // This is where to start the pilot ID from
Config::Set('PILOTID_LENGTH', 4); // The length of PID, including 0's
Config::Set('SHOW_LEG_TEXT', true); // Show the leg text or not
Config::Set('PAGE_EXT', '.htm'); // The page extension
Config::Set('UNITS', 'mi'); // Your units: mi or km

# Google Map Options

/*
 * Valid types are G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP
 */
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
 
# FSPassengers Settings
# Units settings
Config::Set('WeightUnit', '0');   # 0=Kg 1=lbs
Config::Set('DistanceUnit', '2');   # 0=KM 1= Miles 2=NMiles
Config::Set('SpeedUnit', '1');   # 0=Km/H 1=Kts
Config::Set('AltUnit', '1');   # 0=Meter 1=Feet 
Config::Set('LiquidUnit', '2');   # 0=liter 1=gal 2=kg 3=lbs
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
Config::Set('DEBUG_MODE', false);
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);