<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

class Handler
{
    /**
     * @throws \Exception
     */
    public function postInstall()
    {
        $input = new ArrayInput(array('command' => 'update'));
        $application = new Application();
        $application->run($input);
    }

    /**
     * @throws \Exception
     */
    public function postUpdate()
    {
        $input = new ArrayInput(array('command' => 'require', 'packages' => ['javanile/vtiger-composer']));
        $application = new Application();
        $application->run($input);
    }
}
