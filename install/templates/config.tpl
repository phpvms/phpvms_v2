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

Config::Set('PILOTID_OFFSET', 0); // This is where to start the pilot ID from
Config::Set('SHOW_LEG_TEXT', true); // Show the leg text or not
Config::Set('PAGE_EXT', '.htm'); // The page extension
Config::Set('UNITS', 'mi'); // Your units: mi or km

Config::Set('MAP_WIDTH', '600px');
Config::Set('MAP_HEIGHT', '400px');

/*
 * Valid types are G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP
 */
Config::Set('MAP_TYPE', 'G_SATELLITE_MAP');
Config::Set('MAP_LINE_COLOR', '#ff0000');

Config::Set('DEBUG_MODE', false);
?>