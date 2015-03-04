<?php

namespace PipingBag\Module;

use Cake\Network\Request;
use Cake\Network\Response;
use Ray\Di\AbstractModule;

class DefaultModule extends AbstractModule {

	protected $_configuration;

	public function __construct(array $configuration) {
		$this->_configuration = $configuration;
		parent::__construct();
	}

	public function configure() {
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

        $this->bind(__CLASS__)->toInstance($this);
	}

    public function add($foo) {
        $this->install($foo);
    }

}

