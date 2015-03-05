<?php

namespace PipingBag\Module;

use Cake\Network\Request;
use Cake\Network\Response;
use Ray\Di\AbstractModule;

class HttpModule extends AbstractModule {

	protected $_class;

	protected $_request;

	protected $_response;

	public function __construct($class, Request $request, Response $response) {
		$this->_class = $class;
		$this->_request = $request;
		$this->_response = $response;
		parent::__construct();
	}

	public function configure() {
        $this->bind('Cake\Network\Request')->toInstance($this->_request);
        $this->bind('Cake\Network\Response')->toInstance($this->_response);
        $this->bind($this->_class)->toConstructor($this->_class, 'request,response');
	}

}
