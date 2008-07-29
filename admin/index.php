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

/**
 * @author Nabeel Shahzad <www.phpvms.net>
 * @desc Admin panel home
 */
	
define('ADMIN_PANEL', true);

include '../core/codon.config.php';

if(!Auth::LoggedIn())
{
	header('Location: '.SITE_URL.'?page=login&redir=admin');
}

if(!Auth::UserInGroup('Administrators'))
{
	die('Unauthorized access!');
}

Template::SetTemplatePath(dirname(__FILE__).'/templates');
$modules = MainController::getModulesFromPath(dirname(__FILE__).'/modules');
MainController::loadModules($modules);

$BaseTemplate = new TemplateSet;

$skin = 'admin';
//load the main skin
$settings_file = SITE_ROOT . '/lib/skins/'.$skin.'/'.$skin.'.php';
if(file_exists($settings_file))
	include $settings_file;
	
$BaseTemplate->template_path = SITE_ROOT . '/lib/skins/'.$skin;
$BaseTemplate->Set('title', SITE_NAME);


Template::Set('MODULE_NAV_INC', $NAVBAR);
Template::Set('MODULE_HEAD_INC', $HTMLHead);

//$BaseTemplate->Set('navigation_tree', $NAVBAR);
//$BaseTemplate->Set('head_text', $HTMLHead);

$BaseTemplate->Show('header.tpl');

MainController::RunAllActions();

$BaseTemplate->Show('footer.tpl');
 
?>