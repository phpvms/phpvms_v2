<?php



class VACentral extends CodonModule
{
	public function HTMLHead()
	{
		Template::Set('sidebar', 'sidebar_central.tpl');
	}
	
	public function Controller()
	{
		
		switch($this->get->page)
		{
			
			
			case '':
			default:
			
				Template::Show('central_main.tpl');
			
				break;
				
			case 'sendschedules':
			
				$ret = CentralData::send_schedules();
				echo 'Server returned '.$ret;
				
				break;
		}	
	}
		
	
}