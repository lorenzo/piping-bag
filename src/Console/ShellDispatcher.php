<?php
namespace PipingBag\Console;

use Cake\Console\ShellDispatcher as BaseShellDispatcher;
use PipingBag\Di\PipingBag;

class ShellDispatcher extends BaseShellDispatcher {

/**
 * Run the dispatcher
 *
 * @param array $argv The argv from PHP
 * @return int The exit code of the shell process.
 */
	public static function run($argv) {
		$dispatcher = new static($argv);
		return $dispatcher->dispatch();
	}

/**
 * Create the given shell name, and set the plugin property
 *
 * @param string $className The class name to instanciate
 * @param string $shortName The plugin-prefixed shell name
 * @return \Cake\Console\Shell A shell instance.
 */
	protected function _createShell($className, $shortName) {
		list($plugin) = pluginSplit($shortName);
		$instance = PipingBag::get($className);
		$instance->plugin = trim($plugin, '.');
		return $instance;
	}

}
