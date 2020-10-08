<?php

namespace Javanile\VtigerComposer;

use Composer\Console\Application;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Symfony\Component\Console\Input\ArrayInput;

class Logger
{
    public static function log($messages, $timestamp = false)
    {
        $messages = (array) $messages;
        $timestamp = $timestamp ? date('Y-m-d H:i:s').' ' : '';
        foreach ($messages as $message) {
            $message = $timestamp . strip_tags($message);
            file_put_contents('logs/composer.log', $message."\n", FILE_APPEND);
        }
    }

    public static function write($messages, $newline = false)
    {
        $messages = (array) $messages;
        foreach ($messages as $message) {
            $message = strip_tags($message);
            file_put_contents('logs/composer.log', $message . ($newline ? "\n" : ''), FILE_APPEND);
        }
    }

    public static function writeln($messages)
    {
        $messages = (array) $messages;
        foreach ($messages as $message) {
            $message = strip_tags($message);
            file_put_contents('logs/composer.log', $message."\n", FILE_APPEND);
        }
    }
}
