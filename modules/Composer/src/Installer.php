<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

class Installer
{
    /**
     *
     */
    protected $moduleName;

    /**
     *
     */
    protected $composerFile;

    /**
     *
     */
    public function __construct($moduleName, $composerFile)
    {
        $this->moduleName = $moduleName;
        $this->composerFile = $composerFile;
    }

    /**
     * @throws \Exception
     */
    public function install()
    {
        $input = new ArrayInput(array('command' => 'update'));
        $application = new Application();
        $application->run($input);
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $input = new ArrayInput(array('command' => 'require', 'packages' => ['javanile/vtiger-composer']));
        $application = new Application();
        $application->run($input);
    }
}
