<?php

namespace PipingBag\Di;

use Cake\Core\App;
use PipingBag\Module\DefaultModule;
use Ray\Di\AbstractModule;
use Ray\Di\DiCompiler;
use Ray\Di\InjectorInterface;
use Ray\Di\Name;
use Ray\Di\ScriptInjector;
use Ray\Di\Injector;
use Ray\Di\Exception\NotCompiled;

class PipingBag
{

    /**
     * The current injector instance
     *
     * @var InjectorInterface
     */
    protected static $instance;


    /**
     * The modules collection to install in the injector
     *
     * @var DefaultModule
     */
    protected static $modules;

    /**
     * Creates a new injector instance
     *
     * @param array|callable $modules A list of modules to be installed. Or a callable
     * that will return the list of modules.
     * @return Injector
     */
    public static function create($modules = [])
    {
        if (is_callable($modules)) {
            $modules = (array)$modules();
        }

        $modules = new DefaultModule($modules);

        if (empty(static::$instance)) {
            $injector = new Injector($modules);
            static::container($injector);
            self::$modules = $modules;
        } else {
            $injector = new Injector($modules);
        }

        return $injector;
    }

    /**
     * Get/Set the Injector instance.
     *
     * @param Injector $instance The injector to be used.
     * @return Injector
     */
    public static function container(InjectorInterface $instance = null)
    {
        if ($instance !== null) {
            static::$instance = $instance;
        }
        return static::$instance;
    }

    /**
     * Return an instance of a class after resolving its dependencies.
     *
     * @param string $class The class name or interface name to load.
     * @param string $name The alias given to this class for namespacing the configuration.
     * @return mixed
     */
    public static function get($class, $name = Name::ANY)
    {
        try {
            return static::container()->getInstance($class, $name);
        } catch (NotCompiled $e) {
           $compiler = new DiCompiler(self::$modules, TMP);
           $compiler->compile();
           return $compiler->getInstance($class, $name);
        }
    }
}
