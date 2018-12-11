<?php

use PHPUnit\TextUI\TestRunner;

class TestCli {
	
	public function run() {
		
		echo 'Ok';
		
		$phpunit = new TestRunner;
		echo SITE_PATH;
		try {
    			$test_results = $phpunit->dorun($phpunit->getTest(SITE_PATH.'/tests/models/UserTests', '', 'UserTests.php'));
		} catch (PHPUnit_Framework_Exception $e) {
		    print $e->getMessage() . "\n";
		    die ("Unit tests failed.");
		}
	}
	
}
