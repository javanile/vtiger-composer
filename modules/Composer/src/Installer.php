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
     */
    protected $output;

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
        $this->output = Factory::createOutput();
    }

    /**
     * @throws \Exception
     */
    public function install()
    {
        try {
            $this->printLine();
            Logger::log("INSTALL module={$this->moduleName} file={$this->composerFile}", true);
            //$this->composerUpdate();
            $this->updateRootComposerFile();
            //$this->composerDumpAutoload();
            //$this->composerInstall();
            $this->requirePackages();
        } catch (\Exception $e) {

        }
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        try {
            $this->printLine();
            Logger::log("UPDATE module={$this->moduleName} file={$this->composerFile}", true);
            //$this->composerUpdate();
            //$this->updateRootComposerFile();
            //$this->composerDumpAutoload();
            //$this->composerInstall();
            $this->requirePackages();
        } catch (\Exception $e) {

        }
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

            $values = $definition[$mainKey][$subKey];

            foreach ($values as $key => $path) {
                $values[$key] = 'modules/'.$this->moduleName.'/'.$path;
            }

            $rootValues = isset($rootDefinition[$mainKey][$subKey])
                ? array_merge($rootDefinition[$mainKey][$subKey], $values) : $values;

            $manipulator->addSubNode($mainKey, $subKey, $rootValues);
        }
    }

    /**
     *
     */
    protected function requirePackages()
    {
        $definition = $this->composerJson->read();

        $packages = $this->getPackages($definition['require']);

        $this->composerRequire($packages);
    }

    /**
     *
     */
    protected function requireDevPackages()
    {
        $definition = $this->composerJson->read();

        $packages = $this->getPackages($definition['require-dev']);

        $this->composerRequire($packages);
    }

    /**
     *
     */
    protected function getPackages($requireDefinition)
    {
        $packages = [];
        foreach ($requireDefinition as $package => $version) {
            $packages[] = $package.':'.$version;
        }

        return $packages;
    }

    /**
     *
     */
    protected function composerInstall()
    {
        $this->composer(array('command' => 'install'));
    }

    /**
     *
     */
    protected function composerUpdate()
    {
        $this->composer(array('command' => 'update'));
    }

    /**
     *
     */
    protected function composerRequire($packages)
    {
        $this->composer(array('command' => 'require', 'packages' => $packages));
    }

    /**
     *
     */
    protected function composerDumpAutoload()
    {
        $this->composer(array('command' => 'dump-autoload'));
    }

    /**
     * @param $args
     * @throws \Exception
     */
    protected function composer($args)
    {
        putenv('COMPOSER_HOME='.getcwd().'/test/composer');
        ini_set('memory_limit', '4G');

        $input = new ArrayInput($args);
        $application = new Application();
        $application->setAutoExit(false);
        try {
            $application->run($input, $this->output);
        } catch (\Exception $e) {

        }
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
