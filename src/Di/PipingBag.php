<?php

namespace PipingBag\Di;

use Cake\Core\App;
use InvalidArgumentException;
use PipingBag\Cache\CacheAdapter;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class PipingBag {

	protected static $_instance;

	public static function create(array $modules = [], $cacheConfig = 'default') {
		$cacheConfig;
		$cache = $cacheConfig ? new CacheAdapter($cacheConfig) : null;

		if (is_callable($modules)) {
			$modules = $modules();
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

	public static function container(Injector $instance = null) {
		if ($instance !== null) {
			static::$_instance = $instance;
		}
		return static::$_instance;
	}

	public static function get($class) {
		return static::container()->getInstance($class);
	}

	public static function getNamed($class, $name = AbstractModule::NAME_UNSPECIFIED) {
		return static::container()->getNamedInstance($class, $name);
	}

}
