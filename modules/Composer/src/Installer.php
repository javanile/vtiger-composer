<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
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
    protected $composerJson;

    /**
     *
     */
    protected $rootComposerFile;

    /**
     *
     */
    protected $rootComposerJson;

    /**
     *
     *
     * @param $moduleName
     * @param $composerFile
     */
    public function __construct($moduleName, $composerFile)
    {
        $this->moduleName = $moduleName;
        $this->composerFile = $composerFile;
        $this->composerJson = new JsonFile($this->composerFile);
        $this->rootComposerFile = getcwd() . '/composer.json';
        $this->rootComposerJson = new JsonFile($this->rootComposerFile);
    }

    /**
     * @throws \Exception
     */
    public function install()
    {
        echo "\n";
        $this->composerUpdate();

        $this->updateAutoload('autoload');
        $this->updateAutoload('autoload-dev');

        $this->composerDumpAutoload();
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

    /**
     *
     */
    protected function updateAutoload($mainKey)
    {
        $contents = file_get_contents($this->rootComposerJson->getPath());
        $composerDefinition = $this->rootComposerJson->read();
        $manipulator = new JsonManipulator($contents);



        $psr4 = isset($composerDefinition['psr4']) ? $composerDefinition['psr4'] : [];


        $psr4['Ciao\\Coa\\'] = 'src/';

        $manipulator->addSubNode($mainKey, 'psr4', $psr4);

        file_put_contents($this->rootComposerJson->getPath(), $manipulator->getContents());
    }

    /**
     *
     */
    protected function composerInstall()
    {
        $input = new ArrayInput(array('command' => 'update'));
        $application = new Application();
        $application->setAutoExit(false);
        $application->run($input);
    }

    /**
     *
     */
    protected function composerUpdate()
    {
        $input = new ArrayInput(array('command' => 'update'));
        $application = new Application();
        $application->setAutoExit(false);
        $application->run($input);
    }

    /**
     *
     */
    protected function composerDumpAutoload()
    {
        $input = new ArrayInput(array('command' => 'dump-autoload'));
        $application = new Application();
        $application->setAutoExit(false);
        $application->run($input);
    }
}
