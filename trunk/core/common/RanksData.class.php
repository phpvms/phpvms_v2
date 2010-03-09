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

class RanksData extends CodonData
{
	static $lasterror;
	
	/**
	 * Return information about the rank, given the ID
	 */
	public static function getRankInfo($rankid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'ranks
					WHERE rankid='.$rankid;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Returns all the ranks, and the total number of pilots
	 * on each rank
	 */
	public static function getAllRanks()
	{
		$sql = 'SELECT r.*, (SELECT COUNT(*) FROM '.TABLE_PREFIX.'pilots WHERE rank=r.rank) as totalpilots
					FROM ' .TABLE_PREFIX.'ranks r
					ORDER BY r.minhours ASC';
		return DB::get_results($sql);
	}
	
	public static function getRankImage($rank)
	{
		$sql = 'SELECT rankimage FROM '.TABLE_PREFIX.'ranks WHERE rank="'.$rank.'"';
		return DB::get_var($sql);
	}
	
	/**
	 * Give the number of hours, return the next rank
	 */
	public static function getNextRank($hours)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."ranks
					WHERE minhours>$hours ORDER BY minhours ASC LIMIT 1";
		
		return DB::get_row($sql);
	}
	
	/**
	 * Add a ranking. This will automatically call
	 * CalculatePilotRanks() at the end
	 */
	public static function addRank($title, $minhours, $imageurl, $payrate)
	{
		$minhours = intval($minhours);
		$payrate = floatval($payrate);
		
		$sql = "INSERT INTO ".TABLE_PREFIX."ranks (rank, rankimage, minhours, payrate)
					VALUES('$title', '$imageurl', '$minhours', $payrate)";
		
		$ret = DB::query($sql);
		
		if(DB::$errno == 1062)
		{
			self::$lasterror = 'This already exists';
			return false;
		}
		
		self::CalculatePilotRanks();
	
		return true;
	}
	
	/**
	 * Update a certain rank
	 */
	public static function updateRank($rankid, $title, $minhours, $imageurl, $payrate)
	{
		$minhours = intval($minhours);
		$payrate = floatval($payrate);
		
		$sql = "UPDATE ".TABLE_PREFIX."ranks
					SET rank='$title', rankimage='$imageurl', minhours='$minhours', payrate=$payrate
					WHERE rankid=$rankid";
		
		$res = DB::query($sql);
	
		if(DB::errno() != 0)
			return false;
		
		self::CalculatePilotRanks();
		return true;
	}
	
	/**
	 * Delete a rank, and then recalculate
	 */
	 
	public static function DeleteRank($rankid)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'ranks 
					WHERE rankid='.$rankid;
					
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		self::CalculatePilotRanks();
		return true;
	}
	
	/**
	 * Go through each pilot, check their hours, and see where they
	 *  stand in the rankings. If they are above the minimum hours
	 *  for that rank level, then make $last_rank that text. At the
	 *  end, update that
	 */
	public static function CalculatePilotRanks()
	{
		/* Don't calculate a pilot's rank if this is set */
		if(Config::Get('RANKS_AUTOCALCULATE') == false)
		{
			return;
		}
		
		$pilots = PilotData::GetAllPilots();
		$allranks = self::GetAllRanks();
		
		if(!$pilots)
		{
			return;
		}
		
		foreach($pilots as $pilot)
		{
			$last_rank = '';
			
			foreach($allranks as $rank)
			{
				$pilothours = intval($pilot->totalhours);
				
				if(Config::Get('TRANSFER_HOURS_IN_RANKS') == true)
				{
					$pilothours += $pilot->transferhours;
				}
				
				if($pilothours >= intval($rank->minhours))
				{
					$last_rank = $rank->rank;
				}
			}
			
			$sql = 'UPDATE '.TABLE_PREFIX.'pilots
						SET rank="'.$last_rank.'"
						WHERE pilotid='.$pilot->pilotid;
			
			DB::query($sql);
		}
	}
	
	public static function CalculateUpdatePilotRank($pilotid)
	{
		/* Don't calculate a pilot's rank if this is set */
		if(Config::Get('RANKS_AUTOCALCULATE') == false)
		{
			return;
		}
		
		$pilotid = intval($pilotid);
		$allranks = self::GetAllRanks();
		$pilot = PilotData::GetPilotData($pilotid);
		
		foreach($allranks as $rank)
		{
			$pilothours = $pilot->totalhours;
			
			if(Config::Get('TRANSFER_HOURS_IN_RANKS') == true)
			{
				$pilothours += $pilot->transferhours;
			}
			
			if($pilothours >= intval($rank->minhours))
			{
				$last_rank = $rank->rank;
			}
		}
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pilots
					SET rank="'.$last_rank.'"
					WHERE pilotid='.$pilot->pilotid;
		
		DB::query($sql);
	}
}