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
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Autoload\AutoloadGenerator;
use Composer\Package\Version\VersionParser;
use Composer\Downloader\TransportException;
use Seld\JsonLint\JsonParser;
use Composer\Factory as ComposerFactory;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * Creates a configured instance of composer.
 *
 * @author Francesco Bianco <bianco@javanile.org>
 */
class Stderr extends StreamOutput
{
    protected $logger;

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function write($messages, $newline = false, $options = 0)
    {
        if (null !== $this->logger) {
            $this->logger->write($messages, $newline);
        }

        parent::write($messages, $newline, $options);
    }

    public function writeln($messages, $options = 0)
    {
        if (null !== $this->logger) {
            $this->logger->writeln($messages);
        }

        parent::writeln($messages, $options);
    }
}
