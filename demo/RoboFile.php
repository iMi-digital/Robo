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
}