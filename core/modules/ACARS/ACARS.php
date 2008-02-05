<?php


include dirname(__FILE__) . '/ACARSData.class.php';


class ACARS extends ModuleBase
{
	
	function NavBar()
	{
		echo '<li><a href="?page=acars">Live ACARS</a></li>';
	}
	
	function Controller()
	{
			
			if(Vars::Get('page') == 'acars')
			{
				
				//show the el-mapo	
				
			}	
		
	}
}

?>