<?php


class PilotData
{
	
	function GetAllPilots($letter='')
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'users ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";
		
		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	function GetPilotData($id)
	{
		$sql = 'SELECT firstname, lastname, email, location, UNIX_TIMESTAMP(lastlogin) as lastlogin, 
						totalflights, totalhours, confirmed, retired
					FROM '.TABLE_PREFIX.'users WHERE userid='.$id;
		
		return DB::get_row($sql);
	}
	
	function ChangePassword($newpass)
	{
		
	}
}

?>