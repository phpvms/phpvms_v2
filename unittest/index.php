<?php
/*
 * phpVMS Test Suite
 */

include '../core/codon.config.php';
include 'Benchmark/Timer.php';

require_once('krumo/class.krumo.php');
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

# Include all the unit test files and then
#	add the test case
require_once('tests/verify_install.php');
require_once('tests/registration_test.php');
require_once('tests/pirep_test.php');

$test = &new GroupTest('phpVMS API Unit Tests');
$test->addTestCase(new InstallTester);
$test->addTestCase(new RegistrationTester);
$test->addTestCase(new PIREPTester);

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

function __output($var) { __debug($var); }
function __debug($var)
{
	krumo($var);
}

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

$timer = new Benchmark_Timer(true);
$timer->start();

$test->run(new ShowPasses());

$timer->stop();
echo '<br />';
$timer->display();

?>
</body></html>