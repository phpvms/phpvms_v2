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
		
		$where_sql = " WHERE DATE_FORMAT(submitdate, '%c%Y') = '$MonthYear'";	
		
		return self::CalculatePIREPS($where_sql);
	}
	
	/**
	 * Get PIREP financials for the YEAR that's
	 *  in the timestamp. Just pass any timestamp,
	 *  it'll pull the YEAR
	 */
	public static function PIREPForYear($timestamp)
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
		
		# %Y/$Year = Full Year (XXXX)
		$Year = date('Y', $timestamp);
		
		$where_sql = " WHERE DATE_FORMAT(submitdate, '%Y') = '$Year'";	
		
		return self::CalculatePIREPS($where_sql);
	}
	 
	public static function CalculatePIREPS($where='')
	{
		$sql = 'SELECT COUNT(*) AS TotalFlights,
					   ROUND(SUM(p.`pilotpay` * p.`flighttime`), 2) AS TotalPay,
					   ROUND(SUM(p.`price` * p.`load`), 2) AS Revenue
				FROM '.TABLE_PREFIX.'pireps p '.$where;
		
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
	
	# This function is from: http://us.php.net/money_format
	# By Rafael M. Salvioni
	
	function FormatMoney($number)
	{
		$format = Config::Get('MONEY_FORMAT');
		
		$regex  = array(
				'/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?(?:#([0-9]+))?',
				'(?:\.([0-9]+))?([in%])/'
				);
		
		$regex = implode('', $regex);
		if (setlocale(LC_MONETARY, null) == '') {
			setlocale(LC_MONETARY, '');
		}
		$locale = localeconv();
		$number = floatval($number);
		if (!preg_match($regex, $format, $fmatch)) {
			trigger_error("No format specified or invalid format",
					E_USER_WARNING);
			return $number;
		}
		$flags = array(
				'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
				$match[1] : ' ',
				'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
				'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
				$match[0] : '+',
				'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
				'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
				);
		$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
		$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
		$right      = trim($fmatch[4]) ? (int)$fmatch[4] :
			$locale['int_frac_digits'];
		$conversion = $fmatch[5];
		$positive = true;
		if ($number < 0) {
			$positive = false;
			$number  *= -1;
		}
		$letter = $positive ? 'p' : 'n';
		$prefix = $suffix = $cprefix = $csuffix = $signal = '';
		if (!$positive) {
			$signal = $locale['negative_sign'];
			switch (true) {
				case $locale['n_sign_posn'] == 0 || $flags['signal'] ==
					'(':
					$prefix = '(';
					$suffix = ')';
					break;
				case $locale['n_sign_posn'] == 1:
					$prefix = $signal;
					break;
				case $locale['n_sign_posn'] == 2:
					$suffix = $signal;
					break;
				case $locale['n_sign_posn'] == 3:
					$cprefix = $signal;
					break;
				case $locale['n_sign_posn'] == 4:
					$csuffix = $signal;
					break;
			}
		}
		if (!$flags['nosimbol']) {
			$currency  = $cprefix;
			$currency .= (
					$conversion == 'i' ?
					$locale['int_curr_symbol'] :
					$locale['currency_symbol']
					);
			$currency .= $csuffix;
		} else {
			$currency = '';
		}
		$space    = $locale["{$letter}_sep_by_space"] ? ' ' : '';
		
		$number = number_format($number, $right,
				$locale['mon_decimal_point'],
				$flags['nogroup'] ? '' :
				$locale['mon_thousands_sep']
				);
		$number = explode($locale['mon_decimal_point'], $number);
		
		$n = strlen($prefix) + strlen($currency);
		if ($left > 0 && $left > $n) {
			if ($flags['isleft']) {
				$number[0] .= str_repeat($flags['fillchar'], $left - $n);
			} else {
				$number[0] = str_repeat($flags['fillchar'], $left - $n) .
					$number[0];
			}
		}
		$number = implode($locale['mon_decimal_point'], $number);
		if ($locale["{$letter}_cs_precedes"]) {
			$number = $prefix . $currency . $space . $number . $suffix;
		} else {
			$number = $prefix . $number . $space . $currency . $suffix;
		}
		if ($width > 0) {
			$number = str_pad($number, $width, $flags['fillchar'],
					$flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
		}
		$format = str_replace($fmatch[0], $number, $format);
		return $format;
	}
}