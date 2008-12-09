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
Config::Set('UNITS', 'mi'); // Your units: mi or km

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

# Options for the signature that's generated 
Config::Set('SIGNATURE_TEXT_COLOR', '#FFFFFF');
Config::Set('SIGNATURE_SHOW_EARNINGS', true);
Config::Set('SIGNATURE_SHOW_RANK_IMAGE', true);

# Avatar information
Config::Set('AVATAR_FILE_SIZE', 50000);
Config::Set('AVATAR_MAX_WIDTH', 80);
Config::Set('AVATAR_MAX_HEIGHT', 80);

# Debug mode is off by default
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

Config::Set('URL_REWRITE', array(	'default'=>array('module', 'page'),
									'Login'=>array('module', 'page', 'redir'),
								 	'Logout'=>array('module', 'page', 'redir'),
									'Pages'=>array('module', 'page'),
									'PIREPS'=>array('module', 'page', 'id', 'icao'),
									'Pilots'=>array('module', 'page', 'pilotid'),
									'Profile'=>array('module', 'page', 'pilotid'),
									'Schedules'=>array('module', 'page', 'id'),
									'ACARS'=>array('module', 'page', 'action'),
									'XML'=>array('module', 'request')));
							
/**
 * Constants
 */
define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3);

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);