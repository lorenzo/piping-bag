<?php

namespace PipingBag\Provider;

use Ray\Di\Di\Inject;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;

class ControllerProvider implements ProviderInterface
{

    protected static $controllerClass;

    protected $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function get()
    {
        $class = static::$controllerClass;
        return new $class(
        $this->injector->getInstance('Cake\Network\Request', 'current'),
        $this->injector->getInstance('Cake\Network\Response', 'current'),
        null,
        $this->injector->getInstance('Cake\Event\EventManager')
        );
    }

    public static function set($c)
    {
        return static::$controllerClass = $c;
    }
}
