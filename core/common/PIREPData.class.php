<?php



class PIREPData
{
	
	function GetAllReportsByAccept($accept=0)
	{
		$sql = 'SELECT u.pilotid, u.firstname, u.lastname, u.email, u.rank,
					   p.code, p.flightnum, p.depicao, p.arricao, p.flighttime,
					   p.distance, p.submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid AND p.accepted='.$accept;
		
		return DB::get_results($sql);
	}
	
	function GetAllReportsForPilot($pilotid='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps';
		
		if($pilotid!='')
			$sql .=' WHERE pilotid='.intval($pilotid);
			
		return DB::get_results($sql);
	}
	
	/*function GetReportInfo($id)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps 
					WHERE id='.$id;
		
		return DB::get_row($sql);
	}*/
	
	function GetReportDetails($pirepid)
	{
		$sql = 'SELECT u.firstname, u.lastname, u.email, u.rank,
					   p.code, p.flightnum, p.depicao, p.arricao, p.flighttime,
					   p.distance, p.submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid AND p.pirepid='.$pirepid;
		
		return DB::get_row($sql);		
	}

	function GetReportsByAcceptStatus($pilotid, $accept=0)
	{
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps 
					WHERE pilotid='.intval($pilotid).' AND accepted='.intval($accept);
					
		return DB::get_results($sql);		
	}
	
	function GetComments($pirepid)
	{
		$sql = 'SELECT c.comment, c.postdate,
						p.firstname, p.lastname
					FROM '.TABLE_PREFIX.'pirepcomments c, '.TABLE_PREFIX.'pilots p
					WHERE p.pilotid=c.pilotid AND c.pirepid='.$pirepid.'
					ORDER BY postdate ASC';
		
		return DB::get_results($sql);
	}
	
	function FileReport($pilotid, $code, $flightnum, $depicao, $arricao, $flighttime, $comment='')
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."pireps 	
					(pilotid, code, flightnum, depicao, arricao, flighttime, submitdate)
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
	
	function AddComment($pirepid, $commenter, $comment)
	{
	
		$sql = "INSERT INTO ".TABLE_PREFIX."pirepcomments (pirepid, pilotid, comment, postdate)
					VALUES ($pirepid, $commenter, '$comment', NOW())";
		
		DB::query($sql);
		
		return true;		
	}
	
}

?>