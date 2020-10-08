<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Symfony\Component\Console\Input\ArrayInput;

class Logger
{
    public function log($messages, $timestamp = false)
    {
        $messages = (array) $messages;
        $timestamp = $timestamp ? date('Y-m-d H:i:s').' ' : '';
        foreach ($messages as $message) {
            $message = $timestamp . strip_tags($message);
            file_put_contents('logs/composer.log', $message."\n", FILE_APPEND);
        }
    }

    public function write($messages, $newline = false)
    {
        $messages = (array) $messages;
        foreach ($messages as $message) {
            $message = strip_tags($message);
            if ($this->ignoreMessage($message)) {
                continue;
            }
            file_put_contents('logs/composer.log', $message . ($newline ? "\n" : ''), FILE_APPEND);
        }
    }

    /**
     * @param $messages
     */
    public function writeln($messages)
    {
        $this->write($messages, true);
    }

    /**
     * @param $message
     */
    public function ignoreMessage($message)
    {
        if (preg_match("/^[\x08 ]+$/", $message)) {
            return true;
        } elseif (preg_match("/^Downloading \((.+)\)/", $message, $matches)) {
            return !in_array($matches[1], ['100%']);
        }

        return false;
    }
}
