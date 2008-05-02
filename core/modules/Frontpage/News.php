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
 * @package module_news
 */

class News
{
	// This function gets called directly in the template
	function ShowNewsFront()
	{
		
		$sql = 'SELECT id, subject, body, postedby, UNIX_TIMESTAMP(postdate) AS postdate
				 FROM ' . TABLE_PREFIX .'news ORDER BY postdate DESC LIMIT 5';
		
		$res = DB::get_results($sql);
		
		if(!$res)
			return;
			
		foreach($res as $row)
		{
			//TODO: change the date format to a setting in panel
			Template::Set('subject', $row->subject);
			Template::Set('body', $row->body);
			Template::Set('postedby', $row->postedby);
			Template::Set('postdate', date('m/d/Y', $row->postdate));
		
			Template::Show('news_newsitem.tpl');
		}
	}
}