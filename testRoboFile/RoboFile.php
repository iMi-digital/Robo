<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{


    public function testRoboParameters()
    {
        $this->writeParameters('/tmp/test.xml')->run();
    }
    // define public methods as commands
}