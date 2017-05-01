<?php
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
    }

    public function demoConrun()
    {
    	$this->taskConrunStack()->exec('db:info')->run();
    }

    public function demoWp() {
	    $this->_wp('core version');
    }
}
