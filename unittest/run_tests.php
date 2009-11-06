<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

require_once '../core/codon.config.php';
require_once '../core/lib/simpletest/autorun.php';
require_once '../core/lib/simpletest/unit_tester.php';
require_once '../core/lib/simpletest/reporter.php';

$test = &new GroupTest('phpVMS API Unit Tests');

# Include all the test files
$files = glob('tests/*.php');
foreach($files as $file)
{
	include $file;
}

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


function heading($header)
{
	echo "<strong>{$header}</strong><br />";
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

$test->run(new ShowPasses());

?>
</body></html>
