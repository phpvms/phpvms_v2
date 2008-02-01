<?php
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
	public static $err;
	public static $num_rows;
	
	
	public function __destruct()
	{
		self::$DB->close();
	}
	
	public function init($type='mysql')
	{
		
		if($type == 'mysql')
		{
			if(function_exists('mysqli_connect'))
			{
				self::$DB = new ezSQL_mysqli();
			}
			else
			{
				self::$DB = new ezSQL_mysql();
			}
		}
				
		/*if($type == 'mysql')
			self::$DB = new ezSQL_mysql();
		
		if(self::$DB)
			return true;*/
	}
	
	public static function connect($user='', $pass='', $name='', $server='')
	{	
		if($user == '')
			$user = DBASE_USER;
		
		if($pass == '')
			$pass = DBASE_PASS;
		
		if($name == '')
			$name = DBASE_NAME;
		
		if($server == '')
			$server = DBASE_SERVER;
				
		if(!self::$DB->connect($user, $pass, $server))
		{
			die('There was an error connecting to the database!');
		}
		
		if(!self::$DB->select($name))
		{
			die('Selecting '. $name .' didn\'t work');
		}
	}
	
	public static function select($dbname)
	{
		return self::$DB->select($dbname);
	}
	
	public static function close()
	{
		return self::$DB->close();
	}
	
	public static function get_results($query, $type=OBJECT)
	{
		$ret = self::$DB->get_results($query, $type);	
		
		self::$err = self::$DB->err;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		
		return $ret;
	}
		
	public static function get_row($query=null, $output=OBJECT, $y=0)
	{
		$ret = self::$DB->get_row($query, $output, $y);
		
		self::$err = self::$DB->err;
		self::$errno = self::$DB->errno;
		
		return $ret;
	}
	
	public static function query($query)
	{
		self::$insert_id = self::$DB->query($query);
		
		self::$err = self::$DB->err;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		
		return self::$insert_id;
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