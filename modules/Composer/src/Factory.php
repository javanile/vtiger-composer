<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Javanile\VtigerComposer;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\Factory as ComposerFactory;

/**
 * Creates a configured instance of composer.
 *
 * @author Francesco Bianco <bianco@javanile.org>
 */
class Factory
{
    /**
     * Creates a ConsoleOutput instance
     *
     * @return ConsoleOutput
     */
    public static function createOutput()
    {
        $styles = ComposerFactory::createAdditionalStyles();
        $formatter = new OutputFormatter(false, $styles);

        $verbosity = ConsoleOutput::VERBOSITY_NORMAL;
        $output = new Output($verbosity, null, $formatter);
        $stderr = new Stderr($output->getErrorOutput()->getStream(), $verbosity, null, $formatter);

        $output->setErrorOutput($stderr);

        return $output;
    }

    /**
     * Creates a Logger instance
     *
     * @return Logger
     */
    public static function createLogger()
    {
        $logger = new Logger();

        return $logger;
    }
}
