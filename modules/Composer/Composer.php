<?php

if (!class_exists('\\Composer\\Composer')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Javanile\VtigerComposer\Installer as ComposerInstaller;

class Composer
{
    function vtlib_handler($moduleName, $eventType)
    {
        $composerInstaller = new ComposerInstaller($moduleName, __DIR__ . '/composer.json');

        if ($eventType == 'module.postinstall') {
            $composerInstaller->install();
        } else if ($eventType == 'module.preinstall') {

        } else if ($eventType == 'module.postupdate') {
            //$composerInstaller->update();
            $composerInstaller->install();
        } else if ($eventType == 'module.preupdate') {

        } else if ($eventType == 'module.preuninstall') {

        } else if ($eventType == 'module.enabled') {

        } else if ($eventType == 'module.disabled') {

        }
    }
}
