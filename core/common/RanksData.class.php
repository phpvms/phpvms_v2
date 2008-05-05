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
 * @package core_api
 */

class RanksData
{
	
	static $lasterror;
	
	function GetRankInfo($rankid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'ranks WHERE rankid='.$rankid;
		
		return DB::get_row($sql);
	}
	
	function GetAllRanks()
	{
		$sql = 'SELECT r.*, (SELECT COUNT(*) FROM '.TABLE_PREFIX.'pilots WHERE rank=r.rank) as totalpilots
					FROM ' .TABLE_PREFIX.'ranks r 
					ORDER BY r.minhours ASC';
		return DB::get_results($sql);
	}
	
	function GetNextRank($hours)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."ranks WHERE minhours>$hours ORDER BY minhours ASC LIMIT 1";
		return DB::get_row($sql);
	}
	
	function AddRank($hours, $title, $imageurl)
	{
		$hours = intval($hours);
		
		$sql = "INSERT INTO ".TABLE_PREFIX."ranks (rank, rankimage, minhours) VALUES('$title', '$imageurl', '$hours')";
		
		$ret = DB::query($sql);
		
		
		if(DB::$errno == 1062)
		{
			self::$lasterror = 'This already exists';
			return false;
		}
		
		self::CalculatePilotRanks();
	
		return true;
	}
	
	function UpdateRank($rankid, $title, $minhours, $imageurl)
	{
		$sql = "UPDATE ".TABLE_PREFIX."ranks SET rank='$title', rankimage='$imageurl', minhours='$minhours'
					WHERE rankid=$rankid";
		
		DB::query($sql);
	}
	
	/**
	 * Go through each pilot, check their hours, and see where they
	 *  stand in the rankings. If they are above the minimum hours
	 *  for that rank level, then make $last_rank that text. At the
	 *  end, update that
	 */
	function CalculatePilotRanks()
	{
		$pilots = PilotData::GetAllPilots();
		$allranks = self::GetAllRanks();
		
		foreach($pilots as $pilot)
		{
			$last_rank = '';
			
			foreach($allranks as $rank)
			{
				if(intval($pilot->totalhours) >= intval($rank->minhours))
				{
					$last_rank = $rank->rank;
				}
			}
			
			$sql = 'UPDATE '.TABLE_PREFIX.'pilots SET rank="'.$last_rank.'" 
						WHERE pilotid='.$pilot->pilotid;
			
			DB::query($sql);
		}
	}
}

?>