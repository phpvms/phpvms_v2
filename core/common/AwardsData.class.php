<?php


class AwardsData 
{
	
	
	/**
	 * Get all awards
	 *
	 * @return mixed array of objects
	 *
	 */
	public static function GetAllAwards()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'awards';
		return DB::get_results($sql);
	}
	
	public static function GetAwardDetail($awardid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'awards
					WHERE `awardid`='.$awardid;
		return DB::get_row($sql);
	}
	
	/**
	 * Add an award
	 *
	 * @param string $name Award Name
	 * @param string $descrip Description of the award
	 * @param string $image Full link to award image
	 * @return bool bool
	 *
	 */
	public static function AddAward($name, $descrip, $image)
	{
		
		$sql = 'INSERT INTO '.TABLE_PREFIX."awards
						   (`name`, `descrip`, `image`)
					VALUES ('$name', '$descrip', '$image')";
		
		DB::query($sql);		
	}
	
	
	/**
	 * Edit an existing award
	 *
	 * @param int $awardid Award ID
	 * @param string $name Name of the award
	 * @param string $descrip Description of the award
	 * @param string $image Full link to award
	 * @return bool bool
	 *
	 */
	public static function EditAward($awardid, $name, $descrip, $image)
	{
		
		$sql = 'UPDATE '.TABLE_PREFIX."awards
				  SET `name`='$name', `descrip`='$descrip', `image`='$image'
				  WHERE `awardid`=$awardid";
		
		DB::query($sql);		
	}
	
	
	/**
	 * Delete an award, also deletes any granted of it
	 *
	 * @param int $awardid Award ID
	 * @return bool bool
	 *
	 */
	public static function DeleteAward($awardid)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."awards WHERE `awardid`=$awardid";
		DB::query($sql);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."awardsgranted WHERE `awardid`=$awardid";
		DB::query($sql);		
	}
	
}