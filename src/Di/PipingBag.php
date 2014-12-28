<?php

namespace PipingBag\Di;

use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class PipingBag {

	protected static $_instance;

	public static function create(array $modules = [], $cacheConfig = null) {
		$cacheConfig;
		$injector = Injector::create($modules, null, TMP);

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
