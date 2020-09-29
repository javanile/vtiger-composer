<?php

require_once __DIR__.'/vendor/autoload.php';

use Javanile\VtigerComposer\Handler;

class Composer
{
    function vtlib_handler($moduleName, $eventType)
    {
        $handler = new Handler();

        if ($eventType == 'module.postinstall') {
            $handler->postInstall();
        } else if ($eventType == 'module.preinstall') {

        } else if ($eventType == 'module.disabled') {

        } else if ($eventType == 'module.enabled') {

        } else if ($eventType == 'module.preuninstall') {

        } else if ($eventType == 'module.preupdate') {

        } else if ($eventType == 'module.postupdate') {
            $handler->postUpdate();
        }
    }
}
