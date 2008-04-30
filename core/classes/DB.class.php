<?php
/*
Codon PHP Framework
www.nsslive.net/codon

 Software License Agreement (BSD License)
 
 Copyright (c) 2008 Nabeel Shahzad, nsslive.net

 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions
 are met:

 1. Redistributions of source code must retain the above copyright
    notice, this list of conditions and the following disclaimer.
 2.  Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the distribution.
 3. The name of the author may not be used to endorse or promote products
    derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
 
/**
 * LiveFrame - www.nsslive.net
 *	
 * DB Class
 *	Interfaces to the $DB object
 *	Database type (mysql, oracle), doesn't matter
 *
 *	Use as:
 *		self::get_result(...)
 *		self::$DB->get_result(...)
 *		
 *		DB::get_result(...)
 *		$insertid = DB::query(...);
 * 
 * revision updates:
 *	5 - Added
 */

/* If not included:
 */

$THISBASE = dirname(__FILE__);
@include_once($THISBASE.'/SQL.class.php');
@include_once($THISBASE.'/MySQL.class.php');
@include_once($THISBASE.'/MySQLi.class.php');
@include_once($THISBASE.'/Oracle.class.php');
 
class DB 
{
	public static $DB;
	public static $insert_id;
	public static $errno;
	public static $error;
	public static $num_rows;
	
	
	public function __destruct()
	{
		self::$DB->close();
	}
	
	public function init($type='mysql')
	{
		
		if($type == 'mysql' || $type == '')
		{
			if(!self::$DB = new ezSQL_mysql())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
				
				return false;
			}
			
		}
		elseif($type == 'mysqli')
		{
			if(!self::$DB = new ezSQL_mysqli())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
				return false;
			}
		}
		elseif($type == 'oracle')
		{
			if(!self::$DB = new ezSQL_oracle8_9())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
				return false;
			}
		}
		else
		{
			self::$error = 'Invalid database type';
			return false;
		}
		
		return self::$DB;
	}
	
	public static function connect($user='', $pass='', $name='', $server='')
	{					
		if(!self::$DB->connect($user, $pass, $server))
		{
			self::$error = self::$DB->error;
			self::$errno = self::$DB->errno;
			
			return false;
		}
		
		if(!self::$DB->select($name))
		{
			self::$error = self::$DB->error;
			self::$errno = self::$DB->errno;
			
			return false;
		}
		
		return true;
	}
	
	public static function select($dbname)
	{
		$ret = self::$DB->select($dbname);
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		
		return $ret;
	}
	
	public static function close()
	{
		return self::$DB->close();
	}
	
	public static function get_results($query, $type=OBJECT)
	{
		$ret = self::$DB->get_results($query, $type);	
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		
		return $ret;
	}
	
	public static function get_row($query=null, $output=OBJECT, $y=0)
	{
		$ret = self::$DB->get_row($query, $output, $y);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		
		return $ret;
	}
	
	public static function query($query)
	{
		$ret = self::$DB->query($query);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		self::$insert_id = self::$DB->insert_id;
		
		return $ret; //self::$insert_id;
	}
	
	public static function get_col_info($info_type="name",$col_offset=-1)
	{
		return self::$DB->get_col_info($info_type, $col_offset);
	}
	
	public static function get_col($query=null,$x=0)
	{
		return self::$DB->get_col($query, $x);
	}
		
	public static function get_var($query=null, $x=0, $y=0)
	{
		return self::$DB->get_var($query, $x, $y);
	}
	
	public static function num_rows()
	{
		return self::$num_rows;
	}
	
	public static function vardump($mixed='')
	{
		return self::$DB->vardump($mixed);
	}
	
	public static function dumpvar($mixed='')
	{
		return self::$DB->vardump($mixed);
	}
	
	public static function get_cache($query)
	{
		return self::$DB->get_cache($query);
	}
	
	public static function store_cache($query, $is_insert)
	{
		return self::$DB->store_cache($query, $is_insert);	
	}
	
	public static function error()
	{
		return self::$DB->error();
	}
	
	public static function errno()
	{
		return self::$DB->errno();
	}
	
	public static function get_all_errors()
	{
		return self::$DB->get_all_errors();
	}
	
	public static function flush()
	{
		return self::$DB->flush();
	}
	public static function show_errors()
	{
		return self::$DB->show_errors();
	}
	
	public static function hide_errors()
	{
		return self::$DB->hide_errors();
	}
	
	public static function escape($val)
	{
		return self::$DB->escape($val);
	}
	
	public static function debug()
	{
		return self::$DB->debug();
	}
}
?>