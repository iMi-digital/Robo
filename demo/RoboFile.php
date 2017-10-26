<?php

define('IROBO_MIN_VERSION', '1.3.1');

if (\Robo\Robo::APPLICATION_NAME != 'iRobo'
    || \Composer\Semver\Comparator::lessThan(\Robo\Robo::VERSION, IROBO_MIN_VERSION)) {
	echo 'ERROR: This script needs iRobo (not only robo) version ' . IROBO_MIN_VERSION
	     . ' or later - download at http://irobo.imi.de/irobo.phar '. PHP_EOL;
	die(1);
}

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
	use \iMi\RoboPack\LoadTasks;

    public function testRoboParameters()
    {
        $this->writeParameters('/tmp/test.xml')->run();
    }

    public function demoSetup()
    {
    	$this->askSetup();
    }

    public function demoMagerun()
    {
    	$this->taskMagerunStack()->exec('sys:info')->run();
    	$this->_magerun('sys:info');
    }

    public function demoMagerunTwo()
    {
    	$this->taskMagerunTwoStack()->exec('sys:info')->run();
    }

    public function demoMagerunTwoExec()
    {
    	$this->_magerunTwo('sys:info');
    }

    public function demoConrun()
    {
    	$this->taskConrunStack()->exec('db:info')->run();
    }

    public function demoWp() {
	    $this->_wp('core version');
    }

    public function demoArtisan() {

	$this->_artisan('env');

    }
}
