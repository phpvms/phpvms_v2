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
			
				echo '<h3>Sending schedules...</h3>';
				$ret = CentralData::send_schedules();
				$this->parse_response($ret);
				
				break;
				
			case 'sendpireps':
				
				echo '<h3>Sending all PIREPS</h3>';
				$ret = CentralData::send_all_pireps();
				$this->parse_response($ret);
				
				break;
		}	
	}
	
	protected function parse_response($resp)
	{
		$xml = simplexml_load_string($resp);
		
		if($xml->type == 'Success')
		{
			echo "Successfully sent message! (Server said \"{$xml->detail}\")";
		}
		else
		{
			echo "There was an error, server said \"{$xml->detail}\"";
		}		
	}
		
	
}