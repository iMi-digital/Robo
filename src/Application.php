<?php

namespace Robo;

use Robo\Common\IO;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Finder\Finder;

class Application extends SymfonyApplication {
    use IO;
    /**
     * @param string $name
     * @param string $version
     */
    public function __construct( $name, $version ) {
        parent::__construct( $name, $version );

        $this->getDefinition()
             ->addOption(
                 new InputOption( '--simulate', null, InputOption::VALUE_NONE, 'Run in simulated mode (show what would have happened).' )
             );
        $this->getDefinition()
             ->addOption(
                 new InputOption( '--progress-delay', null, InputOption::VALUE_REQUIRED, 'Number of seconds before progress bar is displayed in long-running task collections. Default: 2s.', Config::DEFAULT_PROGRESS_DELAY )
             );

        $this->getDefinition()
             ->addOption(
                 new InputOption( '--define', '-D', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Define a configuration item value.', [] )
             );

        $selfUpdateCommand = new SelfUpdateCommand( 'self:update' );
        $this->add( $selfUpdateCommand );
    }

    protected function getTemplateRoboFiles() {
        $base = __DIR__  . '/../';
        $templates = ['Default' => ''];

        $finder = new Finder();
        $finder->files()->in( $base )->name( '*RoboFileTemplate.php' );
        foreach ( $finder as $file ) {
            $templateName = str_replace('RoboFileTemplate.php', '', $file->getBasename());
            $templates[$templateName] = $file;
        }
        ksort($templates);
        return $templates;
    }

    /**
     * @param string $roboFile
     * @param string $roboClass
     */
    public function addInitRoboFileCommand( $roboFile, $roboClass ) {
        $createRoboFile = new Command( 'init' );
        $createRoboFile->setDescription( "Intitalizes basic RoboFile in current dir" );
        $that = $this;


/*        $createRoboFile->getDefinition()
             ->addOption(
                 new InputOption( '--template', '-T', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Define a configuration item value.', [] )
             );*/

        $createRoboFile->setCode( function () use ( $roboClass, $roboFile, $that ) {
            $output = Robo::output();
            $templates = $that->getTemplateRoboFiles();


            $question = new ChoiceQuestion(
                'Please select the template to be used',
                array_keys($templates),
                0
            );
            $question->setErrorMessage('Choice is %s is invalid.');

            $this->output = Robo::output();
            $choosenTemplate =  $this->doAsk($question);

            $boilerPlate = "/**"
                           . "\n * This is project's console commands configuration for Robo task runner."
                           . "\n *"
                           . "\n * @see http://www.github.com/imi-digital/irobo - http://irobo.imi.de/irobo.phar"
                           . "\n */"
                           . "\n"
                           . "\ndefine('IROBO_MIN_VERSION', '" . Robo::VERSION . "'); // define minimum irobo version here"
                           . "\n"
                           . "\nif (\\Robo\\Robo::APPLICATION_NAME != 'iRobo'"
                           . "\n    || \\Composer\\Semver\\Comparator::lessThan(\\Robo\\Robo::VERSION, IROBO_MIN_VERSION)) {"
                           . "\n    echo 'ERROR: This script needs iRobo, a fork of robo.li, version ' . IROBO_MIN_VERSION"
                           . "\n        . ' or later - download at http://irobo.imi.de/irobo.phar or use irobo self-update to update'. PHP_EOL;"
                           . "\n    die(1);"
                           . "\n}"
                           . "\n";

            $output->writeln( "<comment>  ~~~ Welcome to Robo! ~~~~ </comment>" );
            $output->writeln( "<comment>  " . basename( $roboFile ) . " will be created in the current directory </comment>" );

            if ($choosenTemplate == 'Default') {
                $contents = <<<PHP
<?php

// INSERT: IROBO_BOILERPLATE //

class RoboFile extends \Robo\Tasks {
	use \iMi\RoboPack\LoadTasks;

}

PHP;

            } else {
                $contents = $templates[ $choosenTemplate ]->getContents();
            }

            $contents = str_replace( '// INSERT: IROBO_BOILERPLATE //', $boilerPlate, $contents );

            file_put_contents(
                $roboFile,
                $contents
            );
            $output->writeln( "<comment>  Edit this file to add your commands! </comment>" );
        } );
        $this->add( $createRoboFile );
    }
}
