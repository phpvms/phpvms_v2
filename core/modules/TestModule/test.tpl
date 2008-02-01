<div>
i'm saying something to you: <br />
<?=$testvar;?>

<? 
	TemplateSet::ShowModule('TestModule', "i'm a parameter"); 
	
	//or we can do this:

	MainController::Run('TestModule', 'ShowTemplate', "i'm a parameter");
?>
</div>