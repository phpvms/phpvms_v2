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
ini_set('display_errors', 'on');

define('SITE_ROOT', str_replace('core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH . '/classes');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');
define('CACHE_PATH', CORE_PATH . '/cache');
define('COMMON_PATH', CORE_PATH . '/common');
define('PAGES_PATH', CORE_PATH . '/pages');
define('LIB_PATH', SITE_ROOT.'/lib');

// Include all dependencies
include CLASS_PATH . '/Config.class.php';
include CLASS_PATH . '/CodonAJAX.class.php';
include CLASS_PATH . '/CodonEvent.class.php';
include CLASS_PATH . '/CodonForm.class.php';
include CLASS_PATH . '/CodonModule.class.php';
include CLASS_PATH . '/DB.class.php';
include CLASS_PATH . '/Debug.class.php';
include CLASS_PATH . '/MainController.class.php';
include CLASS_PATH . '/SessionManager.class.php';
include CLASS_PATH . '/Template.class.php';
include CLASS_PATH . '/TemplateSet.class.php';
include CLASS_PATH . '/Util.class.php';
include CLASS_PATH . '/Vars.class.php';

Template::SetTemplatePath(TEMPLATES_PATH);
MainController::loadCommonFolder();

include CORE_PATH . '/app.config.php';
include CORE_PATH . '/local.config.php';

set_error_handler('CatchPHPError');
error_reporting(Config::Get('ERROR_LEVEL'));

if(DBASE_NAME != '' && DBASE_SERVER != '' && DBASE_NAME != 'DBASE_NAME')
{
	DB::init(DBASE_TYPE);
	DB::$table_prefix = TABLE_PREFIX;
	DB::setCacheDir(CACHE_PATH);
	
	if(Config::Get('DEBUG_MODE') == true)
		DB::show_errors();
	else
		DB::hide_errors();
		
	if(!DB::connect(DBASE_USER, DBASE_PASS, DBASE_NAME, DBASE_SERVER))
	{
		die('Database connection failed! ('.DB::$errno.': '.DB::$error.')');
	}
}

include CORE_PATH.'/bootstrap.inc.php';

if(function_exists('pre_module_load'))
	pre_module_load();
	
MainController::loadEngineTasks();

if(function_exists('post_module_load'))
	post_module_load();

define('SKINS_PATH', LIB_PATH.'/skins/' . CURRENT_SKIN);
?>