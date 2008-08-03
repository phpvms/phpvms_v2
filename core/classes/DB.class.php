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
	public static $rows_affected;
	public static $connected = false;
	
	public static $table_prefix = '';
	
	/**
	 * Private contructor, don't allow for
	 * initialization of this class
	 */
	private function __contruct()
	{
		return;
	}
	
	/**
	 * Return the singleton instance of the DB class
	 *
	 * @return object
	 */
	public function getInstance()
	{
		return self::$DB;
	}
	
	public function __destruct()
	{
		self::$DB->close();
	}
	
	
	/**
	 * Initialize the database connection
	 *
	 * @param string $type Either mysql, mysqli, oracle. Default is mysql
	 * @return boolean
	 */
	public static function init($type='mysql')
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
			self::$DB = new ezSQL_mysql();
			self::$error = 'Invalid database type';
			return false;
		}
		
		return true;
	}
	
	public function setCacheDir($dir)
	{
		self::$DB->cache_dir = $dir;
	}
	
	public function enableCache()
	{
		self::$DB->cache_query = true;
		self::$DB->use_disk_cache = true;
	}
	
	public function disableCache()
	{
		self::$DB->cache_query = false;
		self::$DB->use_disk_cache = false;
	}
	
	/**
	 * Connect to database
	 *
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 * @param string $server
	 * @return boolean
	 */
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
		
		self::$connected = true;
		return true;
	}
	
	/**
	 * Select/Change the active database. It's called from
	 * connect(), but can also be changed
	 *
	 * @param string $dbname
	 * @return boolean
	 */
	public static function select($dbname)
	{
		$ret = self::$DB->select($dbname);
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		
		if(self::$errno == 0)
			return true;
		
		return false;
	}
	
	/**
	 * Close the database connector
	 *
	 * @return unknown
	 */
	public static function close()
	{
		return self::$DB->close();
	}
	
	/**
	 * Set a table prefix for the quick_ functions
	 * If it's set to blank (default), then no prefix will be used
	 *
	 * @param unknown_type $prefix
	 */
	public static function set_table_prefix($prefix)
	{
		self::$table_prefix = '';
	}
	
	/**
	 * Do a "quick select".
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table name
	 * @param array $fields Fields to select (array)
	 * @param string $cond Conditions to select for
	 * @param constant $type
	 * @return resultset
	 */
	public static function quick_select($table, $fields, $cond='', $type=OBJECT)
	{
		if($table ==  '') return false;
		//if(!is_array($fields) == false) return false;
	
		$sql = 'SELECT ';
		
		if(is_array($fields))
		{
			$total = count($fields);
			
			for($i=0;$i<$total;$i++)
			{
				$sql .= ' '.$fields[$i].',';
			}
			
			$sql = substr($sql, 0, strlen($sql)-1);
		}
		else
		{
			$sql .= $fields;
		}
		
		$sql .= ' FROM '.self::$table_prefix.$table;
		
		if($cond != '')
			$sql .= ' WHERE '.$cond;
		
		return self::get_results($sql, $type);
	}
	
	/**
	 * Do a "quick update"
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table name
	 * @param array $fields Associative array (column=>value) to update
	 * @param unknown_type $cond Conditions to update on
	 * @return result
	 */
	public static function quick_update($table, $fields, $cond='')
	{
		if($table ==  '') return false;
		//if(!is_array($fields) == false) return false;
	
		$sql = 'UPDATE '.self::$table_prefix.$table.' SET ';
		
		if(is_array($fields))
		{
			foreach($fields as $key=>$value)
			{
				$sql.= "$key=";
				
				if(is_numeric($value) || $value == "NOW()")
					$sql.=$value.',';
				else
					$sql.="'$value',";
				
			}
			
			$sql = substr($sql, 0, strlen($sql)-1);
		}
		else
		{
			$sql .= $fields;
		}
		
		if($cond != '')
			$sql .= ' WHERE '.$cond;
		
		return self::query($sql,$type);
	}
	
	/**
	 * Do a quick insert into a table
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table Name
	 * @param array $fields Associatve arrays of keys to isnert
	 * @param string $flags INSERT flags (DELAYED, etc)
	 * @return result
	 */
	public static function quick_insert($table, $fields, $flags= '')
	{
		if($table ==  '') return false;
		//if(!is_array($fields) == false) return false;
	
		$sql = 'INSERT '. $flags .' INTO '.self::$table_prefix.$table.' ';

		if(is_array($fields))
		{
			foreach($fields as $key=>$value)
			{
				// build both strings
				$cols .= $key.',';
							
				// Quotes or none based on value
				if(is_numeric($value) || $value == 'NOW()')
					$col_values .= "$value,";
				else
					$col_values .= "'$value',";
					
			}
			
			$cols = substr($cols, 0, strlen($cols)-1);
			$col_values = substr($col_values, 0, strlen($col_values)-1);
			
			$sql .= '('.$cols.') VALUES ('.$col_values.')';
		}
		else
		{
			$sql .= $fields;
		}
		
		return self::query($sql);
	}
	
	/**
	 * Return array of results. Default returns array of
	 * objects. Can be ARRAY_A, ARRAY_N or OBJECT, for
	 * array associative, numeric array, or an object.
	 *
	 * @see http://www.nsslive.net/codon/docs/database
	 * @param string $query
	 * @param constant $type Return type
	 * @return array/object
	 */
	public static function get_results($query, $type=OBJECT)
	{
		$ret = self::$DB->get_results($query, $type);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		
		return $ret;
	}
	
	/**
	 * Return a single row
	 *
	 * @param string $query
	 * @param constant $type
	 * @param offset $y
	 * @return unknown
	 */
	public static function get_row($query, $type=OBJECT, $y=0)
	{
		$ret = self::$DB->get_row($query, $type, $y);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		
		return $ret;
	}
	
	/**
	 * Perform a query
	 *
	 * @param unknown_type $query
	 * @return boolean/int Returns true/false, or rows affected
	 */
	public static function query($query)
	{
		$ret = self::$DB->query($query);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$rows_affected = self::$num_rows = self::$DB->num_rows;
		self::$insert_id = self::$DB->insert_id;
		
		return $ret; //self::$insert_id;
	}
	
	/**
	 * Get information about a column
	 *
	 * @param string $info_type
	 * @param int $col_offset
	 * @return unknown
	 */
	public static function get_col_info($info_type="name",$col_offset=-1)
	{
		return self::$DB->get_col_info($info_type, $col_offset);
	}
	
	/**
	 * Return a single value from a query
	 *
	 * @param query $query
	 * @param int $offset
	 * @return unknown
	 */
	public static function get_col($query=null,$offset=0)
	{
		return self::$DB->get_col($query, $offset);
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
	
	/**
	 * Get the error string from the last query
	 *
	 * @return string
	 */
	public static function error()
	{
		return self::$DB->error();
	}

	/**
	 * Return the last query error number
	 *
	 * @return int
	 */
	public static function errno()
	{
		return self::$DB->errno();
	}
	
	/**
	 * Return array of all the errors
	 *
	 * @return array
	 */
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