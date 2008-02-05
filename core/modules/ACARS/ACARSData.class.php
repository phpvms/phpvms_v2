<?php



class ACARSData 
{
	
	function InsertData()
	{
		
		$pilotid = Vars::GET('pnumber');
		
		if($pilotid == '')
			return;
			
		$lat = Vars::GET('lat');
		$long = Vars::GET('long');
		$gs = Vars::GET('GS');
		$alt = Vars::GET('Alt');
		$IATA = Vars::GET('IATA');
		$depAptICAO = Vars::GET('depaptICAO');
		$depApt = Vars::GET('depapt');
		$disDepApt = Vars::GET('disdepapt');
		$timeDepApt = Vars::GET('timedepapt');
		$destAptICAO = Vars::GET('destaptICAO');
		$destApt = Vars::GET('destapt');
		$disDestApt = Vars::GET('disdestapt');
		$timeDestApt = Vars::GET('timedestapt');
		$phase = Vars::GET('detailph');
			
		
		$existing = DB::get_row('SELECT id FROM '.TABLE_PREFIX.'acarspos WHERE pilot_num="'.$pilotid.'"');
		
		//Do results, do a clean insert
		if(!$existing)
		{
			//argh, i hate using double quotes. but its a long query =\
			
			$sql = "INSERT INTO ".TABLE_PREFIX."acarspos (pilot_num, lat, lon, gs, alt, IATA, depaptICAO, depapt,
						disDepApt, timeDepApt, destAptICAO, destApt, disDestApt, timeDestApt, phase)
					VALUES('$pilotid', '$lat', '$long', $gs, $alt, '$IATA', '$depAptICAO', '$depApt',
							$disDepApt, $timeDepApt, '$destAptICAO', '$destApt', $disDestApt, '$timeDestApt',
							'$phase')";
			
			$res = DB::query($sql);
			
			if(!$res)
			{
				//verbose for now
				DB::debug();
			}
		}
		else
		{
			//do an update
			
			$rowid = $existing->id;
			$sql = "UPDATE".TABLE_PREFIX."acarspos SET pilot_num='$pilotid', lat='$lat', lon='$lon', gs=$gs, 
						alt=$alt, IATA='$IATA', depAptICAO='$depAptICAO', depApt='$depApt,
						disDepApt=$disDepApt, timeDepApt='timeDepApt, destAptICAO='$destAptICAO', 
						destApt='$destApt', disDestApt=$disDestApt, timeDestApt=$timeDestApt, phase=$phase
					  WHERE id=".$rowid;
					
			$res = DB::query($sql);
			
			if(!$res)
			{
				//error out?
				
				//verbose for now
				DB::debug();
				
			}
			
		}
	}
	
	
}

?>