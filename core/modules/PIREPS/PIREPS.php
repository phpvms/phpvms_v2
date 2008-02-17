<?php


include  dirname(__FILE__) . '/PIREPData.class.php';

class PIREPS extends ModuleBase
{
	
	function NavBar()
	{
		echo '<li><a href="#">PIREPs</a>
				<ul>
					<li><a href="?page=filepirep">File a PIREP</a></li>
					<li><a href="?page=viewpireps">View PIREPs</a></li>
				</ul>
			  </li>';
	}
	
	function Controller()
	{
		$this->TEMPLATE->template_path = dirname(__FILE__);
		
		
		if(Vars::GET('page') == 'filepirep')
		{
			//TODO: show PIREP page
			
		}
		elseif(Vars::GET('page') == 'viewpireps')
		{
			//TODO: show pireps
		}		
		
	}
}
?>