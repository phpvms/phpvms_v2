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
 
 
class CodonRewrite 
{
	public static $rewrite_rules = array();
	public static $get;
	public static $current_module;
	
	protected static $peices;
	protected static $run=false;
		
	/**
	 * Add a rewrite rule for the module
	 *
	 * @param string $module Module name
	 * @param array $params The rewrite rules in order array('parameter1'=>'type', 'parameter2')
	 *			Type can be 'string', 'int', 'float', optional, blank defaults to string
	 * @return mixed This is the return value description
	 */
	public static function AddRule($module, $params)
	{		
		$set_params=array();
		$module = strtolower($module);
		
		# Format the rules
		foreach($params as $key=>$value)
		{
			if(is_numeric($key))
				$set_params[$value]='string';
			else
				$set_params[$key]=$value;
		}
		
		self::$rewrite_rules[$module] = $set_params;
		
		if(self::$run == true)
		{
			// Reprocess the rules
			self::ProcessModuleRewrite($module);
		}
	}
	
	/**
	 * Process the rewrite rules, store the results 
	 * into self::$get
	 */
	public static function ProcessRewrite()
	{
		$URL = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
		
		# Get everything after the .php/ and before the ?
		$params = explode('.php/', $URL);
		$preg_match = $params[1];
		
		$params = explode('?', $preg_match);
		$preg_match = $params[0];
		
		if($preg_match == '')
		{
			$preg_match = Config::Get('DEFAULT_MODULE');
		}		
						
		self::$peices = explode('/', $preg_match);
	
		$module_name = strtolower(self::$peices[0]);
		
		if($module_name == '') # If it's blank, check $_GET
		{
			$module_name = $_GET['module'];
		}
		
		self::$current_module = $module_name;
		
		$_GET['module'] = $module_name;
		
		# Match from index 1 and up to the rewrite rule:
		self::$get = new stdClass;
		
		if(!array_key_exists($module_name, self::$rewrite_rules))
		{
			$module_name = 'default';
		}
			
		self::ProcessModuleRewrite($module_name);
		
		// Tack any extra query string parameters back on
		$url = substr($_SERVER['REQUEST_URI'],
				strpos($_SERVER['REQUEST_URI'], '?')+1, strlen($_SERVER['REQUEST_URI']));
		
		if($url == $_SERVER['REQUEST_URI'])
		{ // no extra $_GET parameters
			$get_extra = array();
		}
		else
		{
			parse_str($url, $get_extra);
			$_GET = array_merge($_GET, $get_extra);
			
			foreach($_GET as $key=>$value)
			{
				self::$get->$key = $value;
			}
		}	
		
		self::$run = true;	
	}
	
		
	/**
	 * Process an individual module based on the latest rules	
	 *
	 * @param string $module_name Name of the module to re-process
	 * @return mixed This is the return value description
	 *
	 */
	public static function ProcessModuleRewrite($module_name)
	{
		$i=1;
		if(is_array(self::$rewrite_rules[$module_name]))
		{
			foreach(self::$rewrite_rules[$module_name] as $key=>$type)
			{
				$val = self::$peices[$i++];
				
				# Convert to type specified
				if($type == 'int')
					$val = intval($val);
				elseif($type == 'float')
					$val = floatval($val);
				
				self::$get->$key = $val;	
				$_GET[$key] = $val;
			}
		}
	}
}