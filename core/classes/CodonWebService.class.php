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

class CodonWebService
{
	protected $type = 'curl';
	protected $curl;
	public $errors = array();
	public $options = array(
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_AUTOREFERER => true,
		CURLOPT_CONNECTTIMEOUT => 7,
		CURLOPT_HEADER => false,
		CURLOPT_FOLLOWLOCATION => true);
			
	public function __construct()
	{
		if(!$this->curl = curl_init())
		{
			$this->error();
			return false;
		}
		
		curl_setopt_array($this->curl, $this->options);
	}
	
	public function __destruct()
	{
		curl_close($this->curl);
	}
	
	/**
	 * Internal error handler
	 *
	 * @param string $txt Error text
	 */
	protected function error($txt='')
	{
		if($txt != '')
			$last = $txt;
		else
			$last = curl_error() .' ('.curl_errno().')';
		
		$this->errors[] = $last;
		throw new Exception($last);
	}
	
	/**
	 * Set the transfer type (curl or fopen). cURL is better
	 * POST cannot be done by fopen, it will be ignored
	 *
	 * @param string $type curl or fopen
	 * @return unknown
	 */
	public function setType($type = 'curl')
	{
		if($type != 'curl' && $type !='fopen')
		{
			$this->error('Invalid connection type');
			return false;
		}
		
		$this->type = $type;
	}
	
	/**
	 * Set the curl options, that are different from the default
	 *
	 * @param unknown_type $opt
	 * @return unknown
	 */
	public function setOptions($opt)
	{
		if(!$this->curl)
		{
			$this->error('No valid cURL session');
			return false;
		}
		
		curl_setopt_array($this->curl, $opt);
	}
	
	/**
	 * Grab a URL, but use SSL. Returns the reponse
	 *
	 * @param url $url
	 * @param array $params Associative array of key=value
	 * @param string $type post or get (get by default)
	 */
	public function getSSL($url, $params, $type='get')
	{
		// set SSL options and then go
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
		
		$this->get($url, $params, $type);
	}
	
	/**
	 * Grab a URL, return the reponse
	 *
	 * @param url $url
	 * @param array $params Associative array of key=value
	 * @param string $type post or get (get by default)
	 */
	public function get($url, $params='', $type='get')
	{
		if($this->type == 'fopen' && $type != 'post')
		{
			if(ini_get('allow_url_fopen'))
			{
				return file_get_contents($url);
			}
			else
			{
				$this->error('url fopen not allowed, using cURL');
			}
		}
		
		if(!$this->curl)
		{
			$this->error('cURL not initialzied');
			return false;
		}
		
		//encode our url data properly
		if(!$params) $params = array();
		foreach($params as $key=>$value)
		{
			unset($params[$key]);
			
			$key = urlencode($key);
			$value = urlencode($value);
			$params[$key] = $value;
		}
		
		// See if it's post, add those options on
		if(strtolower($type) == 'post')
		{
			curl_setopt($this->curl, CURLOPT_POST, 1);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
		}
		
		curl_setopt ($this->curl, CURLOPT_URL, $url);
		if(($ret = curl_exec($this->curl)) === false)
		{
			$this->error();
			return false;
		}

		return $ret;
	}
	
	/**
	 * Download a file from $url to the $tofile
	 *
	 * @param url $url URL of file to download
	 * @param path $tofile Path of file to download to
	 * @return unknown
	 */
	public function download($url, $tofile)
	{
		
		if($this->type == 'fopen')
		{
			if(file_put_contents($tofile, file_get_contents($url)) == false)
			{
				$this->error('Error getting file');
				return false;
			}
			
			return true;
		}
		
		if(!$this->curl)
			return false;
			
		if(($fp = fopen($tofile, 'wb')) == false)
		{
			$this->error('Error opening '.$tofile);
			return false;
		}
		
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_FILE, $fp);
		curl_setopt ($this->curl, CURLOPT_URL, $url);
		
		if(($ret = curl_exec($this->curl)) === false)
		{
			$this->error();
			unlink($tofile);
			return false;
		}
	}
}
?>