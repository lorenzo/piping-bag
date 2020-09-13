<?php
declare(strict_types=1);

namespace PipingBag\Console;

use Cake\Console\ShellDispatcher as BaseShellDispatcher;
use PipingBag\Di\PipingBag;
use Cake\Console\Shell;

class ShellDispatcher extends BaseShellDispatcher
{

    /**
     * Run the dispatcher
     *
     * @param array $argv The argv from PHP
     *
     * @return int The exit code of the shell process.
     */
    public static function run(array $argv, array $extra = []) : int
    {
        $dispatcher = new static($argv);
        return $dispatcher->dispatch($extra);
    }

    /**
     * Create the given shell name, and set the plugin property
     *
     * @param string $className The class name to instanciate
     *
     * @param string $shortName The plugin-prefixed shell name
     *
     * @return Shell A shell instance.
     */
    protected function _createShell(string $className, string $shortName) : Shell
    {
        list($plugin) = pluginSplit($shortName);
        $instance = PipingBag::get($className);
        $instance->plugin = trim($plugin, '.');
        return $instance;
    }
}
