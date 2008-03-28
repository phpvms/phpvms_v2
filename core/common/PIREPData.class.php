<?php



class PIREPData
{
	
	function GetAllReports($pilotid='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps';
		
		if($pilotid!='')
			$sql .=' WHERE pilotid='.intval($pilotid);
			
		return DB::get_results($sql);
	}
	
	function GetReportInfo($id)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps WHERE id='.$id;
		
		return DB::get_row($sql);
	}
	
	function GetReportsByAcceptStatus($pilotid, $accept=0)
	{
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps WHERE pilotid='.intval($pilotid).' AND accepted='.intval($accept);
		return DB::get_results($sql);		
	}
	
	//code] => VMS [flightnum] => 553 [depicao] => KLAX [arricao] => KJFK [flighttime] => 5.5 [comment]
	function FileReport($pilotid, $code, $flightnum, $depicao, $arricao, $flighttime, $comment='')
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."pireps 	(pilotid, code, flightnum, depicao, arricao, flighttime, submitdate)
					VALUES ($pilotid, '$code', '$flightnum', '$depicao', '$arricao', '$flighttime', NOW())";
		
		$ret = DB::query($sql);
		
		// Add the comment if its not blank
		if($comment!='')
		{
			$pirepid = DB::$insert_id;
			
			$sql = "INSERT INTO ".TABLE_PREFIX."pirepcomments (pirepid, pilotid, comment, postdate)
						VALUES ($pirepid, $pilotid, '$comment', NOW())";
			
			$ret = DB::query($sql);
		}
		
		return true;
	}
	
}

?>