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
		parent::__construct();
		$this->_class = $class;
		$this->_request = $request;
		$this->_response = $response;
	}

	public function configure() {
		$this->bind($this->_class)->toConstructor([
			'request' => $this->_request,
			'response' => $this->_response,
			'name' => null,
			'eventManager' => null
		]);

		$this->bind('Cake\Network\Request')->toCallable(function() {
			return $this->_request;
		});
		$this->bind('Cake\Network\Response')->toCallable(function() {
			return $this->_response;
		});
	}

}
