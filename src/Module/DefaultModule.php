<?php

namespace PipingBag\Module;

use Cake\Network\Request;
use Cake\Network\Response;
use Ray\Di\AbstractModule;
use Cake\Core\App;

class DefaultModule extends AbstractModule {

	protected $_configuration;

	public function __construct(array $configuration) {
		$this->_configuration = $configuration;
		parent::__construct();
	}

	protected function configure() {
        $this->bind('Cake\Event\EventManager');
        $this->install(new HttpModule);

	    array_map(function($module) {
			if (!is_string($module)) {
				return $module;
			}

			$class = App::classname($module, 'Di/Module');

			if (!$class) {
				throw new InvalidArgumentException('Invalid Di module name: ' . $module);
			}

			$this->install(new $class);
		}, $this->_configuration);
	}

    public function add($foo) {
        $this->install($foo);
    }

}

