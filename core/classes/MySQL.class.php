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

/**********************************************************************
 *  Author: Justin Vincent (justin@visunet.ie)
 *  Web...: http://php.justinvincent.com
 *  Name..: ezSQL_mysql
 *  Desc..: mySQL component (part of ezSQL databse abstraction library)
 *
 */
 
/*
 * Modifications by Nabeel Shahzad
 */

/**********************************************************************
*  ezSQL Database specific class - mySQL
*/

class ezSQL_mysql extends ezSQLcore
{

	/**/

	function ezSQL_mysql($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
	{
		//return $this->quick_connect($dbuser, $dbpassword, $dbname, $dbhost);
	}

	/*
	 *  Short hand way to connect to mySQL database server
	 *  and select a mySQL database at the same time
	 
	 
	function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
	{
		$this->dbname = $dbname;
	
		if($dbuser != '' && $dbhost != '')
		{
			if(!$this->connect($dbuser, $dbpassword, $dbhost))
				return false;
		}
		else
		{
			$this->register_error('Username or host not set');
		}
	
		if($this->dbname != '')
		{
			if(!$this->select($this->dbname))
				return false;
		}
	
		return true;
	}*/

	/*
	 *  Try database connection
	 */

	function connect($dbuser='', $dbpassword='', $dbhost='localhost')
	{
		if(!$this->dbh = @mysql_connect($dbhost, $dbuser, $dbpassword, true))
		{
			$this->register_error(mysql_error(), mysql_errno());
			return false;
		}
		else
		{
			$this->clear_errors();
			return true;
		}
		
		return true;
	}

	/*
	 *  Try to select a mySQL database
	 */
	function select($dbname='')
	{
		// Must have a database name
		if ( $dbname == '')
		{
			$this->register_error('No database name specified!');
			return false;
		}
		// Must have an active database connection
		if (! $this->dbh)
		{
			$this->register_error('Can\'t select database, invalid or inactive connection', -1);
			return false;
		}

		if(!@mysql_select_db($dbname,$this->dbh))
		{
			$this->register_error($mysql_error($this->dbh), mysql_errno($this->dbh));
			return false;
		}
		else
		{
			$this->clear_errors();
			return true;
		}
		
		return true;
	}
	
	function close()
	{
		mysql_close($this->dbh);
	}

	/*
	 *  Format a mySQL string correctly for safe mySQL insert
	 *  (no mater if magic quotes are on or not)
	 */

	function escape($str)
	{
		return mysql_escape_string(stripslashes($str));
	}

	/*
	 *  Return mySQL specific system date syntax
	 *  i.e. Oracle: SYSDATE Mysql: NOW()
	 */

	function sysdate()
	{
		return 'NOW()';
	}

	/*
	 *  Perform mySQL query and try to detirmin result value
	 */

	function query($query)
	{
		// Flush cached values..
		$this->flush();

		// For reg expressions
		$query = trim($query);
		$this->last_query = $query;

		// Count how many queries there have been
		$this->num_queries++;

		// Use core file cache function
		if($cache = $this->get_cache($query))
		{
			return $cache;
		}

		// Make sure connection is ALIVEE!
		if (!isset($this->dbh) || !$this->dbh )
		{
			$this->register_error('There is no active database connection!');
			return false;
		}

		// Perform the query via std mysql_query function..
		$this->result = @mysql_query($query);

		// If there is an error then take note of it..
		if(!$this->result)
		{
			// Check the error to number to see if something
			//	actually went wrong
			$errno = mysql_errno();
			
			if($errno == 0)
			{
				$this->clear_errors();
				return true;
			}
				
			$this->register_error(mysql_error(), $errno);
			return false;
		}
		else
		{
			$this->clear_errors();
		}

		// Query was an insert, delete, update, replace
		$is_insert = false;
		if(preg_match("/^(insert|delete|update|replace)\s+/i",$query))
		{
			$this->rows_affected = @mysql_affected_rows();
			$this->insert_id = @mysql_insert_id();
			$this->num_rows = $this->rows_affected;
				
			if($this->insert_id > 0)
				$is_insert = true;
			
			// Return number fo rows affected
			$return_val = $this->rows_affected;
		}
		// Query was a select
		else
		{
			// Take note of column info
			$i=0;
			
			while ($i < @mysql_num_fields($this->result))
			{
				$this->col_info[$i] = @mysql_fetch_field($this->result);
				$i++;
			}
			
			// Store Query Results
			$num_rows=0;
			
			while($row = @mysql_fetch_object($this->result))
			{
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			@mysql_free_result($this->result);
			
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
}
?>