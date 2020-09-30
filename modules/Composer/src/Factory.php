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

use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;
use Composer\IO\IOInterface;
use Composer\Package\Archiver;
use Composer\Package\Version\VersionGuesser;
use Composer\Repository\RepositoryManager;
use Composer\Repository\RepositoryFactory;
use Composer\Repository\WritableRepositoryInterface;
use Composer\Util\Filesystem;
use Composer\Util\Platform;
use Composer\Util\ProcessExecutor;
use Composer\Util\RemoteFilesystem;
use Composer\Util\Silencer;
use Composer\Plugin\PluginEvents;
use Composer\EventDispatcher\Event;
use Javanile\VtigerComposer\Output;
use Seld\JsonLint\DuplicateKeyException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Autoload\AutoloadGenerator;
use Composer\Package\Version\VersionParser;
use Composer\Downloader\TransportException;
use Seld\JsonLint\JsonParser;
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
}
