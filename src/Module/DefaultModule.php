<?php

namespace PipingBag\Module;

use Cake\Core\App;
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
        $this->install(new AssistedModule);
        array_map(function ($module) {
            if (!is_string($module)) {
                return $module;
            }

            $class = App::classname($module, 'Di/Module');

            if (!$class) {
                throw new \InvalidArgumentException('Invalid Di module name: ' . $module);
            }

            $this->install(new $class);
        }, $this->configuration);
    }
}
