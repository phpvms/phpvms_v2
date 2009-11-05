<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon_core
 */
 
session_start();

error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'on');

define('DS', DIRECTORY_SEPARATOR);
define('SITE_ROOT', str_replace('core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH.DS.'classes');
define('TEMPLATES_PATH', CORE_PATH.DS.'templates');
define('MODULES_PATH', CORE_PATH.DS.'modules');
define('CACHE_PATH', CORE_PATH.DS.'cache');
define('COMMON_PATH', CORE_PATH.DS.'common');
define('PAGES_PATH', CORE_PATH.DS.'pages');
define('LIB_PATH', SITE_ROOT.DS.'lib');
define('DOCTRINE_MODELS_PATH', CORE_PATH.DS.'models');

$version = phpversion();
if($version[0] != '5')
{
	die('You are not running PHP 5+');
}

require CLASS_PATH.DS.'autoload.php';
spl_autoload_register('codon_autoload');

Config::Set('MODULES_PATH', CORE_PATH.DS.'modules');
Config::Set('MODULES_AUTOLOAD', true);

require CORE_PATH.DS.'app.config.php';
@include CORE_PATH.DS.'local.config.php';

/* Set the language */
Lang::set_language(Config::Get('SITE_LANGUAGE'));

error_reporting(Config::Get('ERROR_LEVEL'));
Debug::$debug_enabled = Config::Get('DEBUG_MODE');

if(DBASE_NAME != '' && DBASE_SERVER != '' && DBASE_NAME != 'DBASE_NAME')
{
	require CLASS_PATH.DS.'ezDB.class.php';
	DB::$show_errors = Config::Get('DEBUG_MODE');
	DB::$throw_exceptions = false;
	
	DB::init(DBASE_TYPE);
	DB::set_caching(false);
	DB::$table_prefix = TABLE_PREFIX;
	DB::setCacheDir(CACHE_PATH);
	DB::$DB->debug_all = false;
	
	if(Config::Get('DEBUG_MODE') == true)
		DB::show_errors();
	else
		DB::hide_errors();
		
	if(!DB::connect(DBASE_USER, DBASE_PASS, DBASE_NAME, DBASE_SERVER))
	{	
		Debug::showCritical(Lang::gs('database.connection.failed').' ('.DB::$errno.': '.DB::$error.')');
		die();
	}
	
	/* Include doctrine and all of it's options */
	/*include CORE_PATH.DS.'lib'.DS.'doctrine'.DS.'Doctrine.php';
	spl_autoload_register(array('Doctrine', 'autoload'));
	$conn = Doctrine_Manager::connection(DBASE_TYPE.'://'.DBASE_USER.':'.DBASE_PASS.'@'.DBASE_SERVER.'/'.DBASE_NAME);
	$conn->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
	Doctrine::loadModels(DOCTRINE_MODELS_PATH);*/
}

include CORE_PATH.DS.'bootstrap.inc.php';

if(function_exists('pre_module_load'))
	pre_module_load();

MainController::loadEngineTasks();

if(function_exists('post_module_load'))
	post_module_load();

define('SKINS_PATH', LIB_PATH.DS.'skins'.DS.CURRENT_SKIN);