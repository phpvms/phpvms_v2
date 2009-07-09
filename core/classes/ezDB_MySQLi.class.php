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
  * MySQLi implementation for ezDB
  * By Nabeel Shahzad
  */

class ezDB_mysqli extends ezDB_Base
{

	/**
	 * Constructor, connects to database immediately, unless $dbname is blank
	 *
	 * @param string $dbuser Database username
	 * @param string $dbpassword Database password
	 * @param string $dbname Database name (if blank, will not connect)
	 * @param string $dbhost Hostname, optional, default is 'localhost'
	 * @return bool Connect status
	 *
	 */
	public function __construct($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
	{
		if($dbname == '') return false;
		
		parent::__construct();
		
		if($this->connect($dbuser, $dbpassword, $dbhost))
		{
			return $this->select($dbname);
		}
		
		return false;
	}
	
	/**
	 * Explicitly close the connection on destruct
	 */
	
	public function __destruct()
	{
		$this->close();
	}
	
	/**
	 * Connects to database immediately, unless $dbname is blank
	 *
	 * @param string $dbuser Database username
	 * @param string $dbpassword Database password
	 * @param string $dbname Database name (if blank, will not connect)
	 * @param string $dbhost Hostname, optional, default is 'localhost'
	 * @return bool Connect status
	 *
	 */
	public function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
	{
		$this->__construct($dbuser, $dbpassword, $dbname, $dbhost);
	}	
	
	/**
	 * Connect to MySQL, but not to a database
	 *
	 * @param string $dbuser Username
	 * @param string $dbpassword Password
	 * @param string $dbhost Host, optional, default is localhost
	 * @return bool Success
	 *
	 */
	public function connect($dbuser='', $dbpassword='', $dbhost='localhost')
	{
		$this->dbh =  new mysqli($dbhost, $dbuser, $dbpassword);
		
		if(mysqli_connect_errno() != 0)
		{
			if($this->use_exceptions)
				throw new ezDB_Error(mysqli_connect_error(), mysqli_connect_errno());
				
			$this->register_error(mysqli_connect_error(), mysqli_connect_errno());
			return false;
		}
		else
		{
			$this->clear_errors();
			return true;
		}
		
		return true;
	}
	
	/**
	 * Select a MySQL Database
	 *
	 * @param string $dbname Database name
	 * @return bool Success or not
	 *
	 */
	public function select($dbname='')
	{
		// Must have a database name
		if ($dbname == '')
		{
			throw new ezDB_Error('No database name', -1);
			$this->register_error('No database name specified!');
		}
		
		// Must have an active database connection
		if(!$this->dbh)
		{
			if($this->use_exceptions)
				throw new ezDB_Error(mysqli_connect_error(), mysqli_connect_errno());
				
			$this->register_error('Can\'t select database, invalid or inactive connection', -1);
			return false;
		}
		
		if(!$this->dbh->select_db($dbname))
		{
			if($this->use_exceptions)
				throw new ezDB_Error($this->dbh->error, $this->dbh->errno);
				
			$this->register_error($this->dbh->error, $this->dbh->errno);
			return false;
		}
		else
		{
			$this->clear_errors();
			return true;
		}
		
		return true;
	}
	
	/**
	 * Close the database connection
	 */
	public function close()
	{
		return @$this->dbh->close();
	}
	
	/**
	 * Format a mySQL string correctly for safe mySQL insert
	 *  (no matter if magic quotes are on or not)
	 *
	 * @param string $str String to escape
	 * @return string Returns the escaped string
	 *
	 */
	public function escape($str)
	{
		return $this->dbh->real_escape_string($str);
	}
	
	/**
	 * Returns the DB specific timestamp function (Oracle: SYSDATE, MySQL: NOW())
	 *
	 * @return string Timestamp function
	 *
	 */
	public function sysdate()
	{
		return 'NOW()';
	}
	
	/**
	 * Run the SQL query, and get the result. Returns false on failure
	 *  Check $this->error() and $this->errno() functions for any errors
	 *  MySQL returns errno() == 0 for no error. That's the most reliable check
	 *
	 * @param string $query SQL Query
	 * @return mixed Return values
	 *
	 */	
	public function query($query)
	{
		// Initialise return
		$return_val = true;
		
		// Flush cached values..
		$this->flush();
		
		// For reg expressions
		$query = trim($query);
		
		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";
		
		// Keep track of the last query for debug..
		$this->last_query = $query;
		
		// Count how many queries there have been
		$this->num_queries++;
		
		// Use core file cache function
		if($cache = $this->get_cache($query))
		{
			return $cache;
		}
		
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh )
		{
			if($this->use_exceptions)
				throw new ezDB_Error($this->dbh->error, $this->dbh->errno);
				
			$this->register_error('There is no active database connection!');
			return false;
		}
		
		// Perform the query via std mysql_query function..
		$result = $this->dbh->query($query);

		if(is_bool($result))
		{
			if($result === false)
			{
				if($this->use_exceptions)
					throw new ezDB_Error($this->dbh->error, $this->dbh->errno);
					
				$this->register_error($this->dbh->error, $this->dbh->errno);
			}
			else
			{
				$this->clear_errors();
			}
			
			return $result;
		}
				
		// Query was an insert, delete, update, replace
		$is_insert = false;
		if (preg_match("/^(insert|delete|update|replace)\s+/i",$query))
		{
			$this->rows_affected = $this->dbh->affected_rows;
			$this->num_rows = $this->rows_affected;
					
			if($this->dbh->insert_id > 0)
			{
				$this->insert_id = $this->dbh->insert_id;
				$is_insert = true;
			}
			
			// Return number fo rows affected
			$return_val = $this->rows_affected;
		}
		// Query was a select
		else
		{
			// Take note of column info
			$i=0;
			
			if($result)
			{
				while ($finfo = $result->fetch_field())
				{
					$this->col_info[$i] = $finfo;
					$i++;
				}
		
				// Store Query Results
				$num_rows=0;
				while($row = $result->fetch_object())
				{
					$this->last_result[$num_rows] = $row;
					$num_rows++;
				}
				
				$result->close();
			}
			
			// Log number of rows the query returned
			$this->rows_affected = $num_rows;
			$this->num_rows = $num_rows;
			
			// Return number of rows selected
			$return_val = $this->num_rows;
		}
		
		// disk caching of queries
		$this->store_cache($query,$is_insert);
		
		// If debug ALL queries
		$this->trace || $this->debug_all ? $this->debug() : null ;
		
		return $return_val;
	}
	
	
	/* this is mysqli only
	 * incomplete implementation
	 *//*
	function execute($query, $params)
	{
		if($this->mysql_version!=5 || $query == '' || $params == '')
			return;

		$stmt =  $this->dbh->stmt_init();
		if(!$stmt->prepare($query))
			return false;

		//bind our parameters
		while(list($key, $value) = each($params))
		{
			if(is_double($value))
				$type = 'd';
			elseif(is_integer($value) || is_numeric($value))
				$type = 'i';
			else
				$type = 's';

			$stmt->bind_param($type, $value);
		}

		$stmt->execute();

	
	}*/
}

?>