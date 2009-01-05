<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class FinanceData
{	

	public static $lasterror;
	
	public static function GetAllExpenses()
	{		
		$sql = 'SELECT * 
				FROM '.TABLE_PREFIX.'expenses';
		
		return DB::get_results($sql);
	}
	
	public static function AddExpense($name, $cost)
	{
		
		if($name == '' || $cost == '')
		{
			self::$lasterror = 'Name and cost must be entered';
			return false;
		}
		
		$name = DB::escape($name);
		$cost = DB::escape($cost);
		
		$sql = 'INSERT INTO '.TABLE_PREFIX."expenses
					 (`name`, `cost`, `fixed`)
					VALUES('$name', '$cost')";
		
		DB::query($sql);
	}
	
	public static function EditExpense($id, $name, $cost, $fixed)
	{
		
		if($name == '' || $cost == '')
		{
			self::$lasterror = 'Name and cost must be entered';
			return false;
		}
		
		$name = DB::escape($name);
		$cost = DB::escape($cost);
		
		$sql = 'UPDATE '.TABLE_PREFIX."expenses
					SET `name`='$name', `cost`='$cost'
					WHERE `id`=$id";
		
		DB::query($sql);
		
	}
	
	public static function RemoveExpense($id)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'expenses
					WHERE `id`='.$id;
					
		DB::query($sql);
	}
	
	
	/** 
	 * Get the active load count based on the load factor
	 *  based on the flight type: P(assenger), C(argo), H(Charter)
	 */
	public static function GetLoadCount($count, $flighttype='P')
	{
		# Calculate our load factor for this flight
		#	Charter flights always will have a 100% capacity
		if(strtoupper($sched->flighttype) == 'H')
		{
			$load = 100;
		}
		else
		{	# Not a charter
			$loadfactor = intval(Config::Get('LOAD_FACTOR'));
			$load = rand($loadfactor - 10, $loadfactor + 10);
			
			# Don't allow a load of more than 95%
			if($load > 95)
				$load = 95;			
		}
		
		$currload = ceil($count * ($load / 100));
		return $currload;
	}

}