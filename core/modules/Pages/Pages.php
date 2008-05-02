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
 * @package module_pages
 */
 

class Pages extends ModuleBase
{
	

	function NavBar()
	{
		
		
		Template::Set('allpages', SiteData::GetAllPages(true));
		Template::Show('pages_items.tpl');	
		
	}	
	
	
	function Controller()
	{

		if(Vars::GET('page') == 'content' && isset($_GET['p']))
		{
			// Page here is the filename, but we don't call it in directly
			//	for security reasons
			
			$page = Vars::GET('p');
			
			$content = SiteData::GetPageContent($page);
			if(!$content)
			{
				Template::Show('pages_notfound.tpl');
			}
			else
			{
				// Do it this way, so then that this page/template
				//	can be customized on a skin-by-skin basis
				
				Template::Set('pagename', $content->pagename);
				Template::Set('content', $content->content);
				
				Template::Show('pages_content.tpl');
			}
		}
	}
}
?>