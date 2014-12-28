<?php

namespace PipingBag\Di;

use Cake\Core\App;
use InvalidArgumentException;
use PipingBag\Cache\CacheAdapter;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class PipingBag {

/**
 * The current injector instance
 *
 * @var Injector
 */
	protected static $_instance;

/**
 * Creates a new injector instance
 *
 * @param array|callable $modules A list of modules to be installed. Or a callable
 * that will return the list of modules.
 * @param string $cacheConfig The name of the cache config to use.
 * @return Injector
 */
	public static function create($modules = [], $cacheConfig = 'default') {
		$cacheConfig;
		$cache = $cacheConfig ? new CacheAdapter($cacheConfig) : null;

		if (is_callable($modules)) {
			$modules = (array)$modules();
		}

		$modules = array_map(function($module) {
			if (!is_string($module)) {
				return $module;
			}

			$class = App::classname($module, 'Di/Module');

			if (!$class) {
				throw new InvalidArgumentException('Invalid Di module name: ' . $module);
			}

			return new $class;
		}, $modules);

		$injector = Injector::create($modules, $cache, TMP);

		if (empty(static::$_instance)) {
			static::container($injector);
		}

		return $injector;
	}

/**
 * Get/Set the Injector instance.
 *
 * @param Injector $instance The injector to be used.
 * @return Injector
 */
	public static function container(Injector $instance = null) {
		if ($instance !== null) {
			static::$_instance = $instance;
		}
		return static::$_instance;
	}

/**
 * Return an instance of a class after resolving its dependencies.
 *
 * @param $class The class name or interface name to load.
 * @return mixed
 */
	public static function get($class) {
		return static::container()->getInstance($class);
	}

/**
 * Return an instance of a class by a name after resolving its dependencies.
 *
 * @param $class The class name or interface name to load.
 * @param $name The alias given to this class for namespacing the configuration.
 * @return mixed
 */
	public static function getNamed($class, $name = AbstractModule::NAME_UNSPECIFIED) {
		return static::container()->getNamedInstance($class, $name);
	}

}
