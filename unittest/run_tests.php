<?php

require_once '../core/codon.config.php';
require_once '../core/lib/simpletest/autorun.php';
require_once '../core/lib/simpletest/unit_tester.php';
require_once '../core/lib/simpletest/reporter.php';

# Include all the unit test files and then
#	add the test case
require_once('tests/install_test.php');
require_once('tests/registration_test.php');
require_once('tests/pirep_test.php');
require_once('tests/times_test.php');

$test = &new GroupTest('phpVMS API Unit Tests');

$test->addTestCase(new TimesTester);

//$test->addTestCase(new InstallTester);
//$test->addTestCase(new RegistrationTester);
//$test->addTestCase(new PIREPTester);

?>
<html>
<head>
<style>
body { font-family: "Lucida Grande" , Verdana, Geneva, Sans-serif; font-size: 11px; line-height: 1.8em; } 
span { font-weight: bold; }
</style>
</head>
<body>
<?php

class ShowPasses extends HtmlReporter {
	
	public function __construct() {
		parent::__construct();
		$this->HtmlReporter();
	}
	
	public function paintPass($message) {
		parent::paintPass($message);
		echo "<span class=\"pass\">Pass</span>: ";
		$breadcrumb = $this->getTestList();
		array_shift($breadcrumb);
		echo implode("-&gt;", $breadcrumb);
		echo "-&gt;$message<br />\n";
	}
	
	public function getCss() {
		return parent::getCss() . ' .pass { color: green; } ';
	}
	
}

$test->run(new ShowPasses());

?>
</body></html>
