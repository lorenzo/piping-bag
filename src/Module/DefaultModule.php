<?php

namespace PipingBag\Module;

use Cake\Core\App;
use Cake\Network\Request;
use Cake\Network\Response;
use Ray\Di\AbstractModule;

class DefaultModule extends AbstractModule
{

    protected $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        parent::__construct();
    }

    protected function configure()
    {
        $this->bind('Cake\Event\EventManager');
        array_map(function ($module) {
            if (!is_string($module)) {
                return $module;
            }

            $class = App::classname($module, 'Di/Module');

            if (!$class) {
                throw new InvalidArgumentException('Invalid Di module name: ' . $module);
            }

            $this->install(new $class);
        }, $this->configuration);
    }

    public function add($foo)
    {
        $this->install($foo);
    }
}
