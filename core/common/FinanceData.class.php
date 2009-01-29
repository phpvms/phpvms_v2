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
	 * Get the fuel cost, given the airport and the amount of fuel
	 *
	 * @param int $$fuel_amount Amount of fuel used
	 * @param string $apt_icao ICAO of the airport to calculate fuel price
	 * @return int Total cost of the fuel
	 *
	 */
	public static function GetFuelPrice($fuel_amount, $apt_icao='')
	{
		
		if($apt_icao != '')
		{
			$aptinfo = OperationsData::GetAirportInfo($apt_icao);
			
			if($aptinfo->fuelprice == '' || $aptinfo->fuelprice == 0)
				$price = Config::Get('FUEL_DEFAULT_PRICE');
			else
				$price = $aptinfo->fuelprice;
		}
		else
		{
			$price = Config::Get('FUEL_DEFAULT_PRICE');
		}
		
		$total = ($fuel_amount * $price) + ((Config::Get('FUEL_SURCHARGE')/100) * $fuel_amount);
		
		return $total;
	}
	
	/**
	 * Get a year
	 */	
	public static function GetYearBalanceData($yearstamp)
	{
		$ret = array();
		
		$year = date('Y', $yearstamp);
		
		return self::GetRangeBalanceData('January '.$year, 'December '.$year);
	}
	
	
	/**
	 * Get the financial data for a range of dates
	 *  Pass in dates as text, or UNIX Timestamps
	 */
	public static function GetRangeBalanceData($start, $end)
	{
		
		$times = StatsData::GetMonthsInRange($start, $end);
		
		foreach($times as $monthstamp)
		{
			$data = self::GetMonthBalanceData($monthstamp);
			$data['timestamp'] = $monthstamp;
			
			$ret[] = $data;
		}
		
		return $ret;		
	}
	
	/**
	 * Get the financial data for a month
	 *  Pass date as a text, or UNIX Timestamp
	 */
	public static function GetMonthBalanceData($monthstamp)
	{
		$ret = array();
				
		# Check if it's already in our financereports table
		$report = self::GetFinanceData($monthstamp);
		if($report)
		{
			
		}
		else
		{
			# Set the defaults to 0
			$ret['total'] = 0;
			$ret['totalexpenses'] = 0;
			$ret['flightexpenses'] = 0;
			
			# Get fresh copy
			$ret['pirepfinance'] = self::PIREPForMonth($monthstamp);
		
			if($ret['pirepfinance']->TotalFlights == 0)
			{
				return;
			}
				
			$ret['allexpenses'] =  self::GetMonthlyExpenses();
			
			#
			# Do all the calculations
			#
			
			# Start
			$ret['cashreserve'] = 0;
			$ret['total'] = $ret['pirepfinance']->Revenue;
			
			# Subtract the pay
			$ret['total'] -= $ret['allexpenses']->TotalPay;
			# Subtract the flight expenses
			$ret['total'] -= $ret['pirepfinance']->FlightExpenses;
			# Subtract fuel costs
			$ret['total'] -= $ret['pirepfinance']->FuelCost;
			
			# Subtract the total expenses from the total	
			
			if(!$ret['allexpenses']) $ret['allexpenses'] = array();
			foreach($ret['allexpenses'] as $expense)
			{
				$ret['totalexpenses'] += $expense->cost;
			}
			
			$ret['total'] -= $ret['totalexpenses'];
			
			# Save these separately
			$ret['flightexpenses'] += $ret['pirepfinance']->FlightExpenses;
			$ret['fuelcost'] = $ret['pirepfinance']->FuelCost;		
			
			# Save it
			self::AddFinanceData($monthstamp, $ret['pirepfinance'], $ret['allexpenses'], 
									$ret['totalexpenses'], $ret['total']);
		}
		
		return $ret;
	}
	
	/** 
	 * Add a financial expense report
	 */
	public static function AddFinanceData($monthstamp, $pirepdata, $allexpenses, $totalexpenses, $total)
	{
			
		
	}
	
	/**
	 * Get an archived financial expenses report
	 */
	public static function GetFinanceData($monthstamp)
	{
		return false;
	}
	
	/**
	 * Get PIREP financials for the MONTH that's
	 *  in the timestamp. Just pass any timestamp,
	 *  it'll pull the MONTH
	 */	 
	public static function PIREPForMonth($timestamp)
	{
		# Form the timestamp
		if($timestamp == '')
		{
			return false;
		}
		
		# If a numeric date/time is passed
		#	Otherwise, we convert it to a timestamp
		if(!is_numeric($timestamp))
		{
			$timestamp = strtotime($timestamp);
		}
		
		# %c/$Month = Numeric Month (01..12)
		# %Y/$Year = Full Year (XXXX)
		$MonthYear = date('mY', $timestamp);
		
		$where_sql = " WHERE DATE_FORMAT(p.`submitdate`, '%m%Y') = '$MonthYear'";	
		
		return self::CalculatePIREPS($where_sql);
	}
	
	/**
	 * Get PIREP financials for the YEAR that's
	 *  in the timestamp. Just pass any timestamp,
	 *  it'll pull the YEAR
	 * 
	 * DEPRICATED
	 *  "wut? already?" SHEAH
	 *  use GetYearBalanceData instead
	 */
	public static function PIREPForYear($timestamp)
	{		
		return self::GetYearBalanceData($timestamp);
	}
	 
	
	/**
	 * Calculate a PIREP's finances with optional WHERE clause to restrict results
	 *
	 * @param string $where Where clause, to restrict results. Include WHERE
	 * @return array Finances
	 *
	 */
	public static function CalculatePIREPS($where='')
	{
		$sql = 'SELECT COUNT(*) AS TotalFlights,
					   ROUND(SUM(p.`pilotpay` * p.`flighttime`), 2) AS TotalPay,
					   ROUND(SUM(p.`price` * p.`load`), 2) AS Revenue,
					   ROUND(SUM(p.`expenses`)) AS FlightExpenses,
					   ROUND(SUM(p.`fuelprice`)) AS FuelCost
				FROM '.TABLE_PREFIX.'pireps p '.$where;
					
		return DB::get_row($sql);
		DB::debug();
		return $ret;
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
	 * Get monthly expenses
	 */
	 
	public static function GetMonthlyExpenses()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `type`=\'M\'';
		
		return DB::get_results($sql);		
	}
	
	/** 
	 * Get all of the expenses for a flight
	 */
	public static function GetFlightExpenses()
	{
		$sql = "SELECT * 
				FROM ".TABLE_PREFIX."expenses
					WHERE `type`='F'";
					
		return DB::get_results($sql);
	}
	
	/**
	 * Add an expense
	 */
	public static function AddExpense($name, $cost, $type)
	{
		
		if($name == '' || $cost == '')
		{
			self::$lasterror = 'Name and cost must be entered';
			return false;
		}
		
		$name = DB::escape($name);
		$cost = DB::escape($cost);
		$type = strtoupper($type);
		if($type == '')
			$type = 'M'; // Default as monthly
		
		$sql = 'INSERT INTO '.TABLE_PREFIX."expenses
					 (`name`, `cost`, `type`)
					VALUES('$name', '$cost', '$type')";
		
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit a certain expense
	 */
	public static function EditExpense($id, $name, $cost, $type)
	{
		if($name == '' || $cost == '')
		{
			self::$lasterror = 'Name and cost must be entered';
			return false;
		}
		
		$name = DB::escape($name);
		$cost = DB::escape($cost);
		$type = strtoupper($type);
		if($type == '')
			$type = 'M'; // Default as monthly
		
		$sql = 'UPDATE '.TABLE_PREFIX."expenses
					SET `name`='$name', `cost`='$cost', `type`='$type'
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
			$load = rand($loadfactor - LOAD_VARIATION, $loadfactor + LOAD_VARIATION);
			
			# Don't allow a load of more than 95%
			if($load > 95)
				$load = 95;
			elseif($load <= 0)
				$load = 72; # Use ATA standard of 72%
		}
		
		$currload = ceil($count * ($load / 100));
		return $currload;
	}

	function FormatMoney($number)
	{
		$isneg = false;
		if($number < 0)
		{
			$isneg = true;
		}
		
		# $ 50.00 - If positive
		# ($ -50.00)  - If negative
		$number = Config::Get('MONEY_UNIT') .' '.number_format($number, 2, '.', ', ');
		
		if($isneg == true)
			$number = '('.$number.')';
			
		return $number;
	}
}