<?php

/**
 * This is the phpVMS Main Configuration File
 *
 * You can add any additional modules at the bottom
 * This file won't be modified/touched by future versions
 * of phpVMS, you can change your settings here
 * 
 * There may also be additional settings in app.config.php
 * To change it, copy the line into this file here
 *
 */
define('DBASE_USER', '$DBASE_USER');
define('DBASE_PASS', '$DBASE_PASS');
define('DBASE_NAME', '$DBASE_NAME');
define('DBASE_SERVER', '$DBASE_SERVER');
define('DBASE_TYPE', '$DBASE_TYPE');

define('TABLE_PREFIX', '$TABLE_PREFIX');

define('SITE_URL', '$SITE_URL');

# Page encoding options
Config::Set('PAGE_ENCODING', 'ISO-8859-1');

# Maintenance mode - this disables the site to non-admins
Config::Set('MAINTENANCE_MODE', false);
Config::Set('MAINTENANCE_MESSAGE', 'We are currently down for maintenance, please check back soon.');

# See more details about these in the docs
Config::Set('PAGE_EXT', '.htm');	# .htm is fine. You can still run PHP
Config::Set('PILOTID_OFFSET', 0);	# What # to start pilot ID's from
Config::Set('PILOTID_LENGTH', 4);	# Length of the Pilot ID
Config::Set('UNITS', 'nm');			# Your units: nm, mi or km
Config::Set('LOAD_FACTOR', '82');	# %age load factor 
Config::Set('CARGO_UNITS', 'lbs');

# After how long to mark a pilot inactive, in days
Config::Set('PILOT_AUTO_RETIRE', true);
Config::Set('PILOT_INACTIVE_TIME', 90);

# If someone places a bid, whether to disable that or not
Config::Set('DISABLE_SCHED_ON_BID', true);
Config::Set('DISABLE_BIDS_ON_BID', false);

# If you want to count transfer hours in rank calculations
Config::Set('TRANSFER_HOURS_IN_RANKS', false);

# The StatsData::UserOnline() function - how many minutes to check
Config::Set('USERS_ONLINE_TIME', 20);

# Google Map Options
Config::Set('MAP_WIDTH', '800px');
Config::Set('MAP_HEIGHT', '600px');
# Valid types are G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP
Config::Set('MAP_TYPE', 'G_PHYSICAL_MAP');
Config::Set('MAP_LINE_COLOR', '#ff0000');
Config::Set('MAP_CENTER_LAT', '45.484400');
Config::Set('MAP_CENTER_LNG', '-62.334821');
Config::Set('MAP_ZOOM_LEVEL', 12);

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
Config::Set('WeightUnit', '1');   # 0=Kg 1=lbs
Config::Set('DistanceUnit', '2');   # 0=KM 1= Miles 2=NMiles
Config::Set('SpeedUnit', '1');   # 0=Km/H 1=Kts
Config::Set('AltUnit', '1');   # 0=Meter 1=Feet 
Config::Set('LiquidUnit', '2');   # 0=liter 1=gal 2=kg 3=lbs
Config::Set('WelcomeMessage', 'phpVMS/FSPAX ACARS'); # Welcome Message

# Options for the signature that's generated 
Config::Set('SIGNATURE_TEXT_COLOR', '#000');
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

Config::Set('SESSION_LOGIN_TIME', (60*60*24*30)); # Expire after 30 days, in seconds

/* Days of the Week
	The compacted view, and the full text
	DON'T CHANGE THE ORDER!! And yes, Sunday is in there twice
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
