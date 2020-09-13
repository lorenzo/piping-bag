<?php
declare(strict_types=1);

namespace PipingBag\Module;

use Cake\Core\App;
use Ray\Di\AbstractModule;
use InvalidArgumentException;

class DefaultModule extends AbstractModule
{
    protected $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure() : void
    {
        $this->bind('Cake\Event\EventManager');
        $this->install(new AssistedModule);
        array_map(function ($module) {
            if (!is_string($module)) {
                return $module;
            }

            $class = App::classname($module, 'Di/Module');

            if (!$class) {
                throw new InvalidArgumentException('Invalid Dependency Injection module name: ' . $module);
            }

            $this->install(new $class);
        }, $this->configuration);
    }
}
