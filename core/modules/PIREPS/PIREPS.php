<?php


include  dirname(__FILE__) . '/PIREPData.class.php';

class PIREPS extends ModuleBase
{
	
	function NavBar()
	{
		
		echo '<li><a href="#">PIREPs</a>
				<ul>
					<li><a href="?page=filepireps">File a PIREP</a></li>
					<li><a href="?page=viewpireps">View PIREPs</a></li>
				</ul>
			  </li>';
	}
	
	function Controller()
	{
		
		
	}
	
}
?>