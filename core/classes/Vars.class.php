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
 
class Vars
{
	public static $getcount=null;
	public static $postcount=null;
	
	public static $post;
	public static $get;
	public static $request;
	
	public static $rewrite_rules;
	public static $matches;
	
	/**
	 * Set the $post and $get variables, since they will
	 * be set to the same properties in the CodonModule class
	 *
	 */
	public static function setParameters()
	{
		self::$post = new stdClass();
		self::$get =  new stdClass();
		
		foreach($_POST as $key=>$value)
		{
			self::$post->$key = self::cleaned($value);
		}
		
		foreach($_GET as $key=>$value)
		{
			self::$get->$key = self::cleaned($value);
		}
		
	foreach($_REQUEST as $key=>$value)
		{
			self::$request->$key = self::cleaned($value);
		}
	}
	
	public static function URLRewrite($parameters)
	{
		// Parse any ? rules
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
				self::$rewrite_rules->default->$key = $value;
			}
		}
		
		// Now parse any other matches
		
		if(!is_array($parameters))
		{
			return false;
		}
		
		# There was a better way to do this
		#	Die regular expressions, die!
		/*
		 * This matches something like:
		 * /home/url, home/url, home/url, /home/url/
		 * /?([a-zA-Z0-9]+)/?([a-zA-Z0-9]+)/?
		 
		//$pattern = '/\/?';
		//$len = 5;//count($parameters);
		for ($i=0;$i<$len;$i++)
		{
			$pattern .= '([a-zA-Z0-9]*)\/?'; // tack on one for each param
		}
		
		$pattern .= '/i';
		
		$pattern = '/(\w*\/?)';
		*/
		
		# Replace backslashes with forward slashes
		$URL = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
		
		# Get everything after the .php/ and before the ?
		$params = explode('.php/', $URL);
		$preg_match = $params[1];
		
		$params = explode('?', $preg_match);
		$preg_match = $params[0];
		
		if($preg_match == '')
			return true; // nothing behind there
			
		// Match the pattern
		$matches = '';
		//preg_match($pattern, $preg_match, $matches);
		
		$matches = explode('/', $preg_match);
		self::$matches = $matches;
		
		// Loop through each match
		self::$rewrite_rules = new stdClass;
		self::$rewrite_rules->default = new stdClass;
		
		/*// check if this is numeric or not
		if((array_keys($parameters) !== range(0, count($parameters) - 1)))
		{*/
			foreach ($parameters as $pkey=>$pvalue)
			{
				if(is_array($parameters[$pkey]))
				{
					$index = strtolower($pkey);
					self::$rewrite_rules->$index = new stdClass;
					
					$count = count($parameters[$pkey]);
					for($i=0; $i<$count; $i++)
					{
						$name = $parameters[$pkey][$i];
						$temp = $matches[$i+1];
						
						self::$rewrite_rules->$index->$name=$matches[$i];
						$_GET[$name]=$matches[$i];
					}
				}
				/*else
				{
					$index = 'default';
					self::$rewrite_rules->$index->$pvalue=$matches[$pkey+1];
					$_GET[$pvalue] = $matches[$pkey+1];
				}*/
				
				// Write the extra _GET parameters on
				foreach($get_extra as $key=>$value)
				{
					self::$rewrite_rules->$index->$key = $value;
				}
			}
		//}
		/*else
		{
			self::$rewrite_rules->default = new stdClass;
			
			$count = count($parameters);
			for($i=0; $i<$count; $i++)
			{
				$name = $parameters[$i];
				
				self::$rewrite_rules->default->$name = $matches[$i+1];
				$_GET[$name] = $matches[$i+1];
				
				foreach($get_extra as $key=>$value)
				{
					self::$rewrite_rules->default->$key = $value;
				}
			}
		}*/
						
		return true;
	}
	
	public static function cleaned(&$V)
	{
		return htmlspecialchars(addslashes(stripslashes($V)));
	}
	
	/**
	 * Sanitize $var for XSS, JS, and HTML.
	 * 
	 * @param Mixed $var - variable to sanitize  
	 * @return a sanitized variable filtered for XSS and any blacklisted javascript/html tags
	 */
	public static function Filter($var) {
		$filter = new InputFilter;
		return $filter->process(self::cleaned($var));
	}
	
	public static function Request($name)
	{
		return self::cleaned($_REQUEST[$name]);
	}
	
	public static function POST($name)
	{
		if(is_array($_POST[$name]))
		{
			return $_POST[$name];
		}
		
		return self::cleaned($_POST[$name]);
	}
	
	public static function iPOST($name)
	{
		return intval($_POST[$name]);
	}
	
	public static function GET($name)
	{
		if(is_array($_GET[$name]))
		{
			return $_GET[$name];
		}
		
		return self::cleaned($_GET[$name]);
	}
	
	public static function iGET($name)
	{
		return intval($_GET[$name]);
	}
	
	public static function POST_COUNT()
	{
		if(self::$postcount == null)
			self::$postcount = count($_POST);
		
		return self::$postcount;
	}
	
	public static function GET_COUNT()
	{
		if(self::$getcount == null)
			self::$getcount = count($_GET);
		
		return self::$getcount;
	}
}
?>