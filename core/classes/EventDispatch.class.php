<?php



class EventDispatch
{

	static $lastevent;
	
	function Dispatch($eventname, $origin)
	{
		// if there are parameters added, then call the function 
		//	using those additional params
		
		$params=array();
		$params[0] = $eventname;
		$params[1] = $origin;
		
		$args = func_num_args();
		if($args>2)
		{
			for($i=2;$i<$args;$i++)
			{
				$tmp = func_get_arg($i);
				array_push($params, $tmp);
			}
		}		
		
		// Load each module and call the EventListen function
		foreach(MainController::$ModuleList as $ModuleName => $ModuleController)
		{
			$ModuleName = strtoupper($ModuleName);
			global $$ModuleName;
			
			if(method_exists($$ModuleName, 'EventListener'))
			{
				self::$lastevent = $eventname;
				return call_user_method_array('EventListener',  $$ModuleName, $params);
			}
		}
	}


}

?>