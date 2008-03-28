<?
/**
  * Session Manager Module
  *		This handles the backbone of the authentication stuff
  *
  * Sona CMS Framework
  *
  * @author Nabeel Shahzad
  * @link www.sonacms.net
  */
  
  
class SessionManager  
{
	public static $logged_in;
	public static $error_message;
	
	
	/* Register session data
	 *	Also update any data in there
	 */
	function RegisterData(&$info)
	{
		while(list($key, $val) = each($info))
		{
			$_SESSION[$key] = serialize($val);
		}
	}
	
	function AddData($key, $value)
	{
		/*if(is_array($value) || is_object($value))
			$_SESSION[$key] = serialize($value);
		else
			$_SESSION[$key] = $value;*/
			
		$_SESSION[$key] = serialize($value);
	}
	
	function GetData($key)
	{
		return unserialize($_SESSION[$key]);
	}
	
	function GetValue($key, $index)
	{
		$upack = unserialize($_SESSION[$key]);
		
		if(is_object($upack))
			return $upack->$index;
		else
			return $upack[$index];
			
	}
	
	function Logout()
	{
		session_destroy();
	}
}

?>