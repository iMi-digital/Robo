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
        $base = dirname( __DIR__ );

        $templates = [];

        $finder = new Finder();
        $finder->directories()->in( $base )->name( 'robofile-templates' );
        foreach ( $finder as $directory ) {
            $fileFinder = new Finder();
            $fileFinder->files()->in( $directory->getRealPath() )->name( '*.php' );
            foreach ( $fileFinder as $file ) {
                $templates[ $file->getBasename( '.php' ) ] = $file->getRealPath();
            }
        }

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
                'Please select your template',
                array_keys($templates),
                0
            );
            $question->setErrorMessage('Choice is %s is invalid.');

            $this->output = Robo::output();
            $choosenTemplate =  $this->doAsk($question);

            $boilerPlate = "/**"
                           . "\n * This is project's console commands configuration for Robo task runner."
                           . "\n *"
                           . "\n * @see http://robo.li/ and http://www.github.com/imi-digital/irobo"
                           . "\n */"
                           . "\n"
                           . "\ndefine('IROBO_MIN_VERSION', '" . Robo::VERSION . "'); // define minium irobo version here"
                           . "\n"
                           . "\nif (\\Robo\\Robo::APPLICATION_NAME != 'iRobo'"
                           . "\n   || \\Composer\\Semver\\Comparator::lessThan(\\Robo\\Robo::VERSION, IROBO_MIN_VERSION)) {"
                           . "\n   echo 'ERROR: This script needs iRobo (not only robo) version ' . IROBO_MIN_VERSION"
                           . "\n   . ' or later - download at http://irobo.imi.de/irobo.phar '. PHP_EOL;"
                           . "\n   die(1);"
                           . "\n}"
                           . "\n";

            $output->writeln( "<comment>  ~~~ Welcome to Robo! ~~~~ </comment>" );
            $output->writeln( "<comment>  " . basename( $roboFile ) . " will be created in the current directory </comment>" );

            $contents = file_get_contents($templates[$choosenTemplate]);
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
