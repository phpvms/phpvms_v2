<?php

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

Config::Set('PAGE_EXT', '.htm');
Config::Set('PILOTID_OFFSET', 0); // Start the Pilot's ID from 1000
Config::Set('SHOW_LEG_TEXT', true);

// Google Map Options
Config::Set('MAP_WIDTH', '600px');
Config::Set('MAP_HEIGHT', '400px');
Config::Set('MAP_TYPE', 'G_PHYSICAL_MAP');
Config::Set('MAP_LINE_COLOR', '#ff0000');

// Debug mode is off by default
Config::Set('DEBUG_MODE', true);
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);

/**
 * Advanced options, don't edit unless you
 * know what you're doing!!
 */

Config::Set('TEMPLATE_USE_CACHE', false);
Config::Set('TEMPLATE_CACHE_EXPIRE', '24');
Config::Set('DBASE_USE_CACHE', false);
Config::Set('CACHE_PATH', SITE_ROOT . '/core/cache');
Config::Set('RUN_SINGLE_MODULE', true);
Config::Set('DEFAULT_MODULE', 'Frontpage');
Config::Set('MODULES_AUTOLOAD', true);
Config::Set('ACTIVE_MODULES', array());

Config::Set('URL_REWRITE', array('default'=>array('module', 'page'),
								 'Login'=>array('module', 'page', 'redir'),
								 'Logout'=>array('module', 'page', 'redir'),
								 'Pages'=>array('module', 'page'),
								 'PIREPS'=>array('module', 'page', 'id', 'icao'),
								 'Pilots'=>array('module', 'page', 'pilotid'),
								 'Profile'=>array('module', 'page', 'pilotid'),
								 'Schedules'=>array('module', 'page', 'id')
								));
								
/**
 * Constants
 */

define('PHPVMS_VERSION', '1.0.352');

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3);

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

?>