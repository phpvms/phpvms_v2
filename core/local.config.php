<?php

/**
 * This is the phpVMS Main Configuration File
 *
 * You can add any additional modules at the bottom
 * This file won't be modified/touched by future versions
 * of phpVMS, you can change your settings here
 *
 */
define('DBASE_USER', 'root');
define('DBASE_PASS', '');
define('DBASE_NAME', 'phpvms');
define('DBASE_SERVER', 'localhost');
define('DBASE_TYPE', 'mysql');

define('TABLE_PREFIX', 'phpvms_');

define('SITE_URL', 'http://localhost');

Config::Set('PILOTID_OFFSET', 0); // This is where to start the pilot ID from
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
# Minutes, flights to show on the ACARS
# Default is 720 minutes (12 hours)
Config::Set('ACARS_LIVE_TIME', 720); 

# Debug mode is off by default
Config::Set('DEBUG_MODE', false);
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);