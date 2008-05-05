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

$Config['PHPVMS_VERSION'] = '0.9.304';

define('SITE_ROOT', str_replace('/core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH . '/classes');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');
define('CACHE_PATH', CORE_PATH . '/cache');
define('COMMON_PATH', CORE_PATH . '/common');
define('PAGES_PATH', CORE_PATH . '/pages');
define('ADMIN_PATH', SITE_ROOT . '/admin');

if(!file_exists(CORE_PATH.'/local.config.php')
	|| filesize(CORE_PATH.'/local.config.php') == 0)
{
	header('Location: install/install.php');
}

// Include all dependencies
include CLASS_PATH . '/DB.class.php';
include CLASS_PATH . '/EventDispatch.class.php';
include CLASS_PATH . '/JSON.class.php';
include CLASS_PATH . '/MainController.class.php';
include CLASS_PATH . '/ModuleBase.class.php';
include CLASS_PATH . '/SessionManager.class.php';
include CLASS_PATH . '/Template.class.php';
include CLASS_PATH . '/TemplateSet.class.php';
include CLASS_PATH . '/Vars.class.php';
include CLASS_PATH . '/Util.class.php';

include CORE_PATH . '/app.config.php';
include CORE_PATH . '/local.config.php';

if(DBASE_NAME != '')
{
	DB::init(DBASE_TYPE);
	DB::connect(DBASE_USER, DBASE_PASS, DBASE_NAME, DBASE_SERVER);
	DB::hide_errors();
	DB::query('SET FOREIGN_KEY_CHECKS=1;'); // Compensate for host-side setting
}

Auth::StartAuth();
Template::SetTemplatePath(TEMPLATES_PATH);
Util::LoadSiteSettings();

define('LIB_PATH', SITE_ROOT.'/lib');
define('SKINS_PATH', LIB_PATH.'/skins/' . CURRENT_SKIN);
define('CACHE_TIMEOUT', 24); //hours
?>