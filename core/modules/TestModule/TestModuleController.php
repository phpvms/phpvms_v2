<?php

/* This is a test module of the framework
*/

/*Include any other files we need, etc
	for this "Mini App" module
*/

class TestModule extends ModuleBase
{	
	var $TEMPLATES;
	
	function HTMLHead()
	{
		echo '<script type="text/javascript">
	//This is in the <HEAD>
	</script>';
	
	}
	
	function NavBar()
	{
		//This function is picked up by the system
		// Generates a navigation "element" for this module
		echo '<li><a href="?page=pageone">A link</a>
					<ul>
						<li><a href="?page=showprofile">Show Profile</li>
					</ul>
		        </li>';
		        
	}
	
	function Controller()
	{
		echo 'calling controller<br />';
		
		/* you can use _GET and _POST directly, but this
			is a "cleaned" version 
		 */
		if(Vars::POST('action') == 'something')
			echo 'A POST variable';
			
		if(Vars::GET('anotheraction') == '')
			echo 'A GET variable';
			
		//So this module maybe looks for this:
		
		switch(Vars::GET('page'))
		{
			case 'pageone':
				//call some function, do something
				break;
			case 'showprofile':
				$this->TEMPLATES->Set('name', $somename);
				$this->TEMPLATES->ShowTemplate('profile.tpl');
				break;
		}
		
		/* Call another module, say we have a function there we want to call
		 */
		 echo 'running';
		
		//tack on parameters
		$ret = MainController::Run('Classname', 'Method', $parameter1, $parameter2);
		
		//no parameters
		$ret = MainController::Run('Classname', 'Method');
		
		//Or you could just do:
		// but the above is preferred
		global $ModuleName;
		//$ModuleName->Method($param1, $param2, $etc);
		
		/* Database stuff
		 */
		//if we wanna run a query:
		$results = DB::query($sql);
		//or
		$results = DB::get_row($sql);
		//or
		$results = DB::get_results($sql);
		
		foreach($results as $row)
		{
			echo 'value: ' . $row->columnname;
		}
		
		//we can also access the DB object directly:
		$results = DB::$DB->get_results($sql);
		// ...
		
		/* now demonstrate the use of templates
		 */
		// set the template path. might be good to create a 
		// directory called templates inside here, to keep it organized
		$this->TEMPLATE->template_path = dirname(__FILE__);
				
		//register one of the variables we want to use in that templates
		// can be another filename as well, it'll 
		$this->TEMPLATE->set('testvar', 'heyy! this is a test');
		
		//now just output the templates
		// look at the template to see how we can use the "testvar" variable above
		$this->TEMPLATE->ShowTemplate('test.tpl');
	}
	
	function ShowTemplate($parameter1)
	{
		/* Call this from within any template as
		 *	TemplateSet::ShowModule('TestModule', 'I am parameter 1');
		 * 		or as
		 *	MainController::Run("TestModule", "ShowTemplate", "im a parameter");
		 * 
		 * We can call MainController::Run for any function that's made
		 *  With as many parameters, etc as specified
		 */
		 
		echo 'This will show '.$parameter1;
	}
}
?>