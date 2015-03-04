<?php

use Cake\Core\Configure;
use Cake\Routing\DispatcherFactory;
use PipingBag\Di\PipingBag;

$config = Configure::consume('PipingBag');
$modules = !empty($config['modules']) ? $config['modules'] : [];
$cache = isset($config['cacheConfig']) ? $config['cacheConfig'] : 'default';

$instance = PipingBag::create($modules, $cache);
DispatcherFactory::add(
    $instance->getInstance('PipingBag\Routing\Filter\ControllerFactoryFilter')
);
