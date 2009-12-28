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

class FinanceData extends CodonData
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
	public static function getFuelPrice($fuel_amount, $apt_icao='')
	{
		
		/* A few steps:
			Check the local cache, if it's not there, or older
			than three days, reach out to the API, if not there,
			then use the localized price
		 */
		if($apt_icao != '')
		{
			$price = FuelData::GetFuelPrice($apt_icao);
		}
		else
		{
			$price = Config::Get('FUEL_DEFAULT_PRICE');
		}
		
		$total = ($fuel_amount * $price) + ((Config::Get('FUEL_SURCHARGE')/100) * $fuel_amount);
		
		return $total;
	}
	
		
	/**
	 * This gets all of the balance data for a year
	 *
	 * @param int $yearstamp Any timestamp which is in the year you want
	 * @return array All the year's balance data
	 *
	 */
	public static function getYearBalanceData($yearstamp)
	{
		$year = date('Y', $yearstamp);
		
		return self::GetRangeBalanceData('January '.$year, 'December '.$year);
	}
 
	
	/**
	 * Get the financial data for a range of dates
	 *  Pass in dates as text, or UNIX Timestamps
	 * 
	 * @param string $start Start date (any date inside that month)
	 * @param string $end End date (any date inside that month)
	 * @return mixed This is the return value description
	 *
	 */
	public static function getRangeBalanceData($start, $end)
	{
		$times = StatsData::GetMonthsInRange($start, $end);
		$now = time();
		
		$start = StatsData::GetStartDate();
		if(!$start)
			$start = intval(date('Ym'));
		else
			$start = intval(date('Ym', strtotime($start->submitdate)));
			
		$now = intval(date('Ym'));
		
		foreach($times as $monthstamp)
		{
			# Check if we're asking for something that's before
			#	The first PIREP, or after this current month
			
			$c_monthstamp = intval(date('Ym', $monthstamp));
			if($c_monthstamp < $start || $c_monthstamp > $now)
			{
				$data = array('timestamp'=>$monthstamp);
			}
			else
			{
				$data = self::GetMonthBalanceData($monthstamp);
				$data['timestamp'] = $monthstamp;
			}
			
			$ret[] = $data;
		}
		
		return $ret;		
	}
	
	/**
	 * Get the financial data for a month
	 *  Pass date as a text, or UNIX Timestamp
	 * 
	 * This will automatically handle the caching
	 */
	public static function getMonthBalanceData($monthstamp)
	{
		$ret = array();
					
		# Check if it's already in our financedata table
		$report = self::GetCachedFinanceData($monthstamp);
		if(is_object($report))
		{
			$ret = unserialize(stripslashes($report->data));
		}
		else
		{
			# Set the defaults to 0
			$ret['total'] = 0;
			$ret['totalexpenses'] = 0;
			$ret['flightexpenses'] = 0;
			
			# Get fresh copy
			$ret['pirepfinance'] = self::PIREPForMonth($monthstamp);
						
				
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
				// Percentage, of total revenue made and make that
				if($expense->type == 'P')
				{
					$ret['totalexpenses'] += $ret['total'] * ($expense->cost/100);
					continue;
				}
					
				$ret['totalexpenses'] += $expense->cost;
			}
			
			$ret['total'] -= $ret['totalexpenses'];
			
			# Save these separately
			$ret['flightexpenses'] += $ret['pirepfinance']->FlightExpenses;
			$ret['fuelcost'] = $ret['pirepfinance']->FuelCost;		
			
			# Save it, with all the above calculations
			#	Remains intact
			self::AddFinanceDataCache($monthstamp, $ret);
		}
		
		return $ret;
	}
	
	/**
	 * Get an archived financial expenses report
	 */
	public static function getCachedFinanceData($monthstamp)
	{
		if(!is_int($monthstamp))
			$submit = strtotime($monthstamp);
		else
			$submit = $monthstamp;
			
		$submitperiod =  date('Ym', $submit);
		$nowperiod = date('Ym');
				
		if($submitperiod >= $nowperiod)
		{
			return false;
		}
		
		$month = date('m', $submit);
		$year = date('Y', $submit);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX."financedata
					WHERE `month`='$month' AND `year`='$year'";
		
		$ret = DB::get_row($sql);
		
		return $ret;
	}
	
	
	/**
	 * Add financial data into the cache, saving time and queries
	 *
	 * @param int $monthstamp Any timestamp inside the month you are storing
	 * @param mixed $data This is a description
	 * @return mixed This is the return value description
	 *
	 */
	public static function AddFinanceDataCache($monthstamp, $data)
	{		
	
		if(!is_int($monthstamp))
			$submit = strtotime($monthstamp);
		else
			$submit = $monthstamp;
			
		// Don't cache it for the current month
		//	or any months ahead of it
		$submitperiod =  date('Ym', $submit);
		$nowperiod = date('Ym');
				
		if($submitperiod >= $nowperiod)
		{
			return false;
		}
				
		$month = date('m', $submit);
		$year = date('Y', $submit);
		
		$total = $data['total'];
		$data = DB::escape(serialize($data));
		
		# Check if it exists
		#	Update it if it does
		$sql = 'SELECT id FROM '.TABLE_PREFIX."financedata
					WHERE `month`='$month' AND `year`='$year'";
					
		$res = DB::get_row($sql);
		if($res)
		{
			$sql = 'UPDATE '.TABLE_PREFIX."financedata
						SET `month`='$month', 
							`year`='$year',
							`data`='$data',
							`total`='$total'";
		}
		else
		{
			$sql = 'INSERT INTO '.TABLE_PREFIX."financedata
								(`month`, `year`, `data`, `total`)
						VALUES	('$month', '$year', '$data', '$total')";
		}
		
		DB::query($sql);	
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
		if($where == '')
		{
			$where = ' WHERE p.accepted='.PIREP_ACCEPTED;
		}
		else
		{
			$where .= ' AND p.accepted='.PIREP_ACCEPTED;
		}
		
		$sql = 'SELECT COUNT(*) AS TotalFlights,
					   ROUND(SUM(p.`pilotpay` * p.`flighttime`), 2) AS TotalPay,
					   ROUND(SUM(p.`price` * p.`load`), 2) AS Revenue,
					   ROUND(SUM(p.`expenses`)) AS FlightExpenses,
					   ROUND(SUM(p.`fuelprice`)) AS FuelCost
				FROM '.TABLE_PREFIX.'pireps p '.$where;
					
		return DB::get_row($sql);
	}	
	
	/**
	 * Get a list of all the expenses
	 */
	public static function getAllExpenses()
	{		
		$sql = 'SELECT * 
				FROM '.TABLE_PREFIX.'expenses';
		
		return DB::get_results($sql);
	}
	
	/** 
	 * Get an expense details based on ID
	 */
	public static function getExpenseDetail($id)
	{		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `id`='.$id;
					
		return DB::get_row($sql);
	}
	
	/**
	 * Get an expense by the name (mainly to check for
	 *	duplicates)
	 */
	public static function getExpenseByName($name)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `name`='.$name;
					
		return DB::get_row($sql);
	}
	
	/**
	 * Get monthly expenses
	 */
	 
	public static function getMonthlyExpenses()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `type`=\'M\' OR `type`=\'P\'';
		
		return DB::get_results($sql);		
	}
	
	public static function get_total_monthly_expenses()
	{
		$sql = 'SELECT SUM(cost) as cost, COUNT(*) as total
				 FROM '.TABLE_PREFIX.'expenses
				 WHERE `type`=\'M\'';
		
		return DB::get_row($sql);
	}
	
	public static function getPercentExpenses()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'expenses
					WHERE `type`=\'P\'';
		
		return DB::get_results($sql);		
	}
	
	public static function get_total_percent_expenses()
	{
		$sql = 'SELECT SUM(cost) as cost, COUNT(*) as total
				 FROM '.TABLE_PREFIX.'expenses
				 WHERE `type`=\'P\'';
		
		return DB::get_row($sql);
	}
	
	/** 
	 * Get all of the expenses for a flight
	 */
	public static function getFlightExpenses()
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."expenses WHERE `type`='F'";
		return DB::get_results($sql);
	}
	
	
	/**
	 *  Get any percentage expenses per flight
	 *
	 */
	public static function getFlightPercentExpenses()
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."expenses WHERE `type`='G'";
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
	public static function getLoadCount($aircraft_id, $flighttype='P')
	{
		
		$flighttype = strtoupper($flighttype);
		
		# Calculate our load factor for this flight
		#	Charter flights always will have a 100% capacity
		if($flighttype == 'H')
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
		
		/*
		 * Get the maximum allowed based on the aircraft flown
		 */
		$aircraft = OperationsData::GetAircraftInfo($aircraft_id);
	
		if(!$aircraft) # Aircraft doesn't exist
		{
			if($flighttype == 'C') # Check cargo if cargo flight
				$count = Config::Get('DEFAULT_MAX_CARGO_LOAD');
			else
				$count = Config::Get('DEFAULT_MAX_PAX_LOAD');
		}
		else 
		{
			if($flighttype == 'C') # Check cargo if cargo flight
				$count = $aircraft->maxcargo;
			else
				$count = $aircraft->maxpax;
		}
		
		
		$load = ($load / 100);
		$currload = ceil($count * $load);
		return $currload;
	}

	function formatMoney($number)
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