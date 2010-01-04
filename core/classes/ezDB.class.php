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

include dirname(__FILE__).'/ezDB_Base.class.php';


/**
 * This is the < PHP 5.3 version
 *
 */
class DB
{
	public static $DB;
	public static $insert_id;
	public static $errno;
	public static $error;
	public static $num_rows;
	public static $rows_affected;
	public static $connected = false;
	public static $last_query;
	
	public static $throw_exceptions = true;
	public static $default_type = OBJECT;
	public static $show_errors = false;
	
	public static $table_prefix = '';
	
	/**
	 * Private contructor, don't allow for
	 * initialization of this class
	 */
	private function __contruct()
	{
		return;
	}
	
	public function __destruct()
	{
		@self::$DB->close();
	}
	
	/* So it passes through to the main class */
	/*public function __get($name)
	{
		return self::$DB->{$name};
	}
	
	public function __set($name, $value)
	{
		self::$DB->{$name} = $value;
	}
	
	public static function __call($name, $arguments)
	{
		if(method_exists(self::$DB, $name))
		{
			return call_user_func_array(array(self::$DB, $name), $arguments);
		}
	}*/
	
	/**
	 * Return the singleton instance of the DB class
	 *
	 * @return object
	 */
	public static function get_instance()
	{
		return self::$DB;
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
			include dirname(__FILE__).DIRECTORY_SEPARATOR.'ezDB_MySQL.class.php';
			
			if(!self::$DB = new ezDB_mysql())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
			
				return false;
			}
			
			return true;
		}
		elseif($type == 'mysqli')
		{
			include dirname(__FILE__).DIRECTORY_SEPARATOR.'ezDB_MySQLi.class.php';
			
			if(!self::$DB = new ezDB_mysqli())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
				return false;
			}
			
			return true;
		}
		elseif($type == 'oracle')
		{
			include dirname(__FILE__).DIRECTORY_SEPARATOR.'ezDB_Oracle.class.php';
			
			if(!self::$DB = new ezDB_oracle8_9())
			{
				self::$error = self::$DB->error;
				self::$errno = self::$DB->errno;
				return false;
			}
			
			return true;
		}
		else
		{
			include dirname(__FILE__).DIRECTORY_SEPARATOR.'ezDB_MySQL.class.php';
			
			self::$DB = new ezDB_mysql();
			self::$error = 'Invalid database type';
			return true;
		}
		
		return true;
	}
		
	/**
	 * Enable or disable caching, can be set per-query
	 * 
	 * @param bool $bool True/False
	 * @return none
	 */
	public static function set_caching($bool)
	{
		self::$DB->set_caching($bool);
	}
	
	/**
	 * Set the cache type (file, memcache)
	 *
	 * @param string $type Caching type
	 * @return none 
	 *
	 */
	public static function cache_type($type)
	{
		self::$DB->cache_type($type);
	}
	
	/**
	 * Set the path of the cache
	 *
	 * @param mixed $path This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	public static function set_cache_dir($path)
	{
		self::$DB->set_cache_dir($path);
	}
	
		
	/* Aliases for above, backwards compat */
	
	public static function setCacheDir($path)
	{
		self::set_cache_dir($path);
	}
	
	public static function enableCache()
	{
		self::$DB->set_caching(true);
	}
	
	public static function disableCache()
	{
		self::$DB->set_caching(false);
	}
	
	/* End aliases */
	
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
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
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
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
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
		return @self::$DB->close();
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
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		return self::$DB->quick_select($table, $fields, $cond, $type);
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
	public static function quick_insert($table, $fields, $flags= '', $allowed_cols='')
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		return self::$DB->quick_insert($table, $fields, $flags, $allowed_cols);
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
	public static function quick_update($table, $fields, $cond='', $allowed_cols='')
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		return self::$DB->quick_update($table, $fields, $cond, $allowed_cols);
	}
	
	
	/**
	 * Build a WHERE clause for an SQL statement with supplied parameters
	 *
	 * @param array $fields associative array with column=>value
	 * @return string string where
	 *
	 */
	public static function build_where($fields)
	{
		$sql='';
		
		if(!is_array($fields) || empty($fields))
		{
			return false;
		}
		
		$sql .= ' WHERE ';
		
		$where_clauses = array();
		foreach($fields as $column_name => $value)
		{
			# Convert to $columnname IN ($value)
			if(is_array($value))
			{
				$sql_temp = "{$column_name} IN (";
				
				$value_list = array();
				foreach($value as $in)
				{
					$in = DB::escape($in);
					$value_list[] = "'{$in}'";
				}
				
				$sql_temp .= implode(',', $value_list).")";
				$where_clauses[] = $sql_temp;
			}
			else
			{
				# If there's no value per-say, just a field value
				if(is_int($column_name))
				{
					$where_clauses[] = $value;
					continue;
				}
				
				# If there's a % (wildcard) in there, so it should use a LIKE
				if(substr_count($value, '%') > 0)
				{
					$value = DB::escape($value);
					$where_clauses[] = "{$column_name} LIKE '{$value}'";
					continue;
				}
				
				# If it's a greater than or equal to, or for some reason an equals
				if($value[0] == '<' || $value[0] == '>' || $value[0] == '=')
				{
					$where_clauses[] = "{$column_name} {$value}";
					continue;
				}
				
				$value = DB::escape($value);
				$where_clauses[] = "{$column_name} = '{$value}'";
			}
		}
			
		$sql.= implode(' AND ', $where_clauses);
		unset($where_clauses);
		
		return $sql;
	}
	
	
	/**
	 * Build the update clause (after the SET and before WHERE)
	 *
	 * @param array $fields associative array (col_name=>value)
	 * @return string the SQL string
	 *
	 */
	public static function build_update($fields)
	{
		if(!is_array($fields) || empty($fields))
		{
			return false;
		}
		
		$sql = '';
		$sql_cols = array();
		
		foreach($fields as $col => $value)
		{
			$tmp = "`{$col}`=";
			if($value == 'NOW()')
			{
				$tmp.='NOW()';
			}
			else
			{
				$value = DB::escape($value);
				$tmp.="'{$value}'";
			}
			
			$sql_cols[] = $tmp;
		}
		
		$sql .= implode(', ', $sql_cols);
		unset($sql_cols);
		
		return $sql;
	}
	
	/**
	 * Write out the last query to a debug log, or error
	 *
	 * @return mixed This is the return value description
	 *
	 */
	public static function write_debug()
	{
		$log = debug_backtrace();
		$call_list = array();
		
		foreach($log as $caller)
		{
			$call_list[] = $caller['class'].$caller['type'].$caller['function'];
		}
		$callers = implode('->', $call_list);
		unset($call_list);
		
		Debug::log("Caller: ".$callers);
		Debug::log(self::$last_query."\n".self::$error);
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
	public static function get_results($query, $type='')
	{
		if($type == '') $type = self::$default_type;
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->get_results($query, $type);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
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
	public static function get_row($query, $type='', $y=0)
	{
		if($type == '') $type = self::$default_type;
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->get_row($query, $type, $y);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
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
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->query($query);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$rows_affected = self::$num_rows = self::$DB->num_rows;
		self::$insert_id = self::$DB->insert_id;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
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
	
	public static function debug($return = false)
	{
		if(self::$show_errors === true)
			return self::$DB->debug($return);
	}
}