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
 



class PIREPData
{

	function GetAllReports($start=0, $count=20)
	{
		$sql = 'SELECT p.pirepid, u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						p.code, p.flightnum, p.depicao, p.arricao, p.flighttime, p.aircraft,
						p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid LIMIT '.$start.', '.$count;

		return DB::get_results($sql);
	}

	function GetAllReportsByAccept($accept=0)
	{
		$sql = 'SELECT p.pirepid, u.pilotid, u.firstname, u.lastname, u.email, u.rank,
						p.code, p.flightnum, p.depicao, p.arricao, p.flighttime, p.aircraft,
						p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid AND p.accepted='.$accept;

		return DB::get_results($sql);
	}

	function GetRecentReportsByCount($count = 10)
	{
		if($count == '') $count = 10;

		$sql = 'SELECT p.pirepid, u.pilotid, u.firstname, u.lastname, u.email, u.rank,
					   p.code, p.flightnum, p.depicao, p.arricao, p.flighttime, p.aircraft,
					   p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid
					ORDER BY p.submitdate DESC
					LIMIT '.intval($count);

		return DB::get_results($sql);
	}

	function GetRecentReports($days=2)
	{
		$sql = 'SELECT p.pirepid, u.pilotid, u.firstname, u.lastname, u.email, u.rank,
					   p.code, p.flightnum, p.depicao, p.arricao, p.flighttime, p.aircraft,
					   p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					WHERE p.pilotid=u.pilotid
						AND DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY) <= p.submitdate
					ORDER BY p.submitdate DESC';

		return DB::get_results($sql);
	}

	/**
	 * Get the number of reports on a certain date
	 *  Pass unix timestamp
	 */
	function GetReportCount($date)
	{
		$sql = 'SELECT COUNT(*) AS count FROM '.TABLE_PREFIX.'pireps
					WHERE DATE(submitdate)=DATE(FROM_UNIXTIME('.$date.'))';

		$row = DB::get_row($sql);
		return $row->count;
	}

	function GetCountsForDays($days = 7)
	{
		$sql = 'SELECT DISTINCT(DATE(submitdate)) AS submitdate,
					(SELECT COUNT(*) FROM '.TABLE_PREFIX.'pireps WHERE DATE(submitdate)=DATE(p.submitdate)) AS count
				FROM '.TABLE_PREFIX.'pireps p WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= p.submitdate';

		return DB::get_results($sql);
	}

	function GetAllReportsForPilot($pilotid)
	{
		/*$sql = 'SELECT pirepid, pilotid, code, flightnum, depicao, arricao, aircraft,
					   flighttime, distance, UNIX_TIMESTAMP(submitdate) as submitdate, accepted
					FROM '.TABLE_PREFIX.'pireps';*/
		$sql = 'SELECT p.pirepid, u.firstname, u.lastname, u.email, u.rank,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong,
					   p.code, p.flightnum, p.depicao, p.arricao, p.aircraft, p.flighttime,
					   p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						INNER JOIN phpvms_airports AS dep ON dep.icao = p.depicao
						INNER JOIN phpvms_airports AS arr ON arr.icao = p.arricao
					WHERE p.pilotid=u.pilotid AND p.pilotid='.intval($pilotid).'
					ORDER BY p.submitdate DESC';

		return DB::get_results($sql);
	}

	function ChangePIREPStatus($pirepid, $status)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'pireps
					SET accepted='.$status.' WHERE pirepid='.$pirepid;

		return DB::query($sql);
	}

	function GetReportDetails($pirepid)
	{
		$sql = 'SELECT u.firstname, u.lastname, u.email, u.rank,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong,
					   p.code, p.flightnum, p.depicao, p.arricao, p.aircraft, p.flighttime,
					   p.distance, UNIX_TIMESTAMP(p.submitdate) as submitdate, p.accepted
					FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
						INNER JOIN phpvms_airports AS dep ON dep.icao = p.depicao
						INNER JOIN phpvms_airports AS arr ON arr.icao = p.arricao
					WHERE p.pilotid=u.pilotid AND p.pirepid='.$pirepid;

		return DB::get_row($sql);
	}

	function GetLastReports($pilotid, $count = 1)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps
					WHERE pilotid='.intval($pilotid).'
					ORDER BY submitdate DESC
					LIMIT '.intval($count);

		if($count == 1)
			return DB::get_row($sql);
		else
			return DB::get_results($sql);
	}

	function GetReportsByAcceptStatus($pilotid, $accept=0)
	{

		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pireps
					WHERE pilotid='.intval($pilotid).' AND accepted='.intval($accept);

		return DB::get_results($sql);
	}

	function GetComments($pirepid)
	{
		$sql = 'SELECT c.comment, UNIX_TIMESTAMP(c.postdate) as postdate,
						p.firstname, p.lastname
					FROM '.TABLE_PREFIX.'pirepcomments c, '.TABLE_PREFIX.'pilots p
					WHERE p.pilotid=c.pilotid AND c.pirepid='.$pirepid.'
					ORDER BY postdate ASC';

		return DB::get_results($sql);
	}

	function FileReport($pilotid, $code, $flightnum, $depicao, $arricao, $aircraft, $flighttime, $comment='')
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."pireps
					(pilotid, code, flightnum, depicao, arricao, aircraft, flighttime, submitdate)
					VALUES ($pilotid, '$code', '$flightnum', '$depicao', '$arricao', '$aircraft', '$flighttime', NOW())";

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