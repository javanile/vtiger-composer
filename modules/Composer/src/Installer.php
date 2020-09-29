<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
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
        $this->printLine();
        //$this->composerUpdate();
        $this->updateRootComposerFile();
        $this->composerDumpAutoload();
        $this->composerInstall();
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->printLine();
        //$this->composerUpdate();
        $this->updateRootComposerFile();
        $this->composerDumpAutoload();
        $this->composerInstall();
    }

    /**
     *
     */
    protected function updateRootComposerFile()
    {
        $this->printLine('Updating root composer.json file');
        $contents = file_get_contents($this->rootComposerJson->getPath());
        $manipulator = new JsonManipulator($contents);
        $definition = $this->composerJson->read();
        $rootDefinition = $this->rootComposerJson->read();

        $this->updateAutoload('autoload', $manipulator, $definition, $rootDefinition);
        $this->updateAutoload('autoload-dev', $manipulator, $definition, $rootDefinition);

        file_put_contents($this->rootComposerJson->getPath(), $manipulator->getContents());
    }

    /**
     * @param $mainKey
     * @param $manipulator
     * @param $definition
     * @param $rootDefinition
     */
    protected function updateAutoload($mainKey, $manipulator, $definition, $rootDefinition)
    {
        foreach (['psr-4', 'psr-0', 'classmap', 'files'] as $subKey) {
            if (empty($definition[$mainKey][$subKey])) {
                continue;
            }

            $values = isset($rootDefinition[$mainKey][$subKey])
                ? array_merge($rootDefinition[$mainKey][$subKey], $definition[$mainKey][$subKey])
                : $definition[$mainKey][$subKey];

            $manipulator->addSubNode($mainKey, $subKey, $values);
        }
    }

    /**
     *
     */
    protected function composerInstall()
    {
        $input = new ArrayInput(array('command' => 'install'));
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

    /**
     * @param $string
     */
    protected function printLine($string)
    {
        if (php_sapi_name() === 'cli') {
            echo $string."\n";
        }
    }
}
