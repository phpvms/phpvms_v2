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
	
	
	/**
	 * Get the total payment for PIREPS
	 */
	 
	public static function CalculatePIREPS()
	{
		$sql = 'SELECT COUNT(*) AS TotalFlights,
					   ROUND(SUM(p.`pilotpay` * p.`flighttime`), 2) AS TotalPay,
					   ROUND(SUM(p.`price` * p.`load`), 2) AS Revenue
				FROM '.TABLE_PREFIX.'pireps p';
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get a list of all the expenses
	 */
	public static function GetAllExpenses()
	{		
		$sql = 'SELECT * 
				FROM '.TABLE_PREFIX.'expenses';
		
		return DB::get_results($sql);
	}
	
	/** 
	 * Get an expense details based on ID
	 */
	public static function GetExpenseDetail($id)
	{		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `id`='.$id;
					
		return DB::get_row($sql);
	}
	
	/**
	 * Get an expense by the name (mainly to check for
	 *	duplicates)
	 */
	public static function GetExpenseByName($name)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `name`='.$name;
					
		return DB::get_row($sql);
	}
	
	/**
	 * Add an expense
	 */
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
					 (`name`, `cost`)
					VALUES('$name', '$cost')";
		
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit a certain expense
	 */
	public static function EditExpense($id, $name, $cost)
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
		
		if(DB::errno() != 0)
			return false;
		
		return true;		
	}
	
	/**
	 * Delete an expense
	 */
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
			elseif($load <= 0)
				$load = 72; # Use ATA standard of 72%
		}
		
		$currload = ceil($count * ($load / 100));
		return $currload;
	}
}