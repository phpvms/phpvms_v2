<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
 */
 



class RanksData
{
	
	static $lasterror;
	
	function GetRankInfo($rankid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots WHERE rankid='.$rankid;
		
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
	
	function AddRank($hours, $title)
	{
		$hours = intval($hours);
		$sql = "INSERT INTO ".TABLE_PREFIX."ranks (rank, minhours) VALUES('$title', '$hours')";
		
		$ret = DB::query($sql);
		
		
		if(DB::$errno == 1062)
		{
			self::$lasterror = 'This already exists';
			return false;
		}
		
		self::CalculatePilotRanks();
	
		return true;
	}
	
	function UpdateRank($rankid, $title, $minhours)
	{
		$sql = "UPDATE ".TABLE_PREFIX."ranks SET rank='$title', minhours='$minhours'
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