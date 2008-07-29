<?php

Config::Add('PIREP_PENDING', 0);
Config::Add('PIREP_ACCEPTED', 1);
Config::Add('PIREP_REJECTED', 2);
Config::Add('PIREP_INPROGRESS', 3); // value of 3

Config::Add('PILOT_PENDING', 0);
Config::Add('PILOT_ACCEPTED', 1);
Config::Add('PILOT_REJECTED', 2);

Config::Add('PAGE_EXT', '.htm');
Config::Add('PILOTID_OFFSET', 0); // Start the Pilot's ID from 1000

Config::Add('MAP_WIDTH', '600px');
Config::Add('MAP_HEIGHT', '400px');

// Debug mode is off by default
Config::Add('DEBUG_MODE', false);
Config::Add('ERROR_LEVEL', E_ALL ^ E_NOTICE);

// Advanced options
Config::Add('TEMPLATE_USE_CACHE', false);
Config::Add('TEMPLATE_CACHE_EXPIRE', '24');
Config::Add('DBASE_USE_CACHE', false);
Config::Add('CACHE_PATH', SITE_ROOT . '/core/cache');
Config::Add('RUN_SINGLE_MODULE', false);
Config::Add('MODULES_AUTOLOAD', true);
Config::Add('ACTIVE_MODULES', array());
?>