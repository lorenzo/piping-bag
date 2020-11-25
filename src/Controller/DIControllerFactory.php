<?php
declare(strict_types=1);

namespace PipingBag\Controller;


use Cake\Controller\Controller;
use Cake\Controller\ControllerFactory;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use PipingBag\Di\PipingBag;

class DIControllerFactory extends ControllerFactory {

    public function create(ServerRequestInterface $request): Controller {
        $controller = parent::create($request);
        if(!$request instanceof ServerRequest) {
            return $controller;
        }

        $controllerClass = $this->getControllerClass($request);
        
        $instance = PipingBag::get($controllerClass);
        $instance->setName($controller->getName());
        $instance->setRequest($controller->getRequest());
        $instance->setResponse($controller->getResponse());
        return $instance;
    }
}