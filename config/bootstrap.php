<?php

use Cake\Core\Configure;
use Cake\Routing\DispatcherFactory;
use PipingBag\Di\PipingBag;
use PipingBag\Routing\Filter\ControllerFactoryFilter;

$config = Configure::consume('PipingBag');
$modules = !empty($config['modules']) ? $config['modules'] : [];
$cache = isset($config['cacheConfig']) ? $config['cacheConfig'] : 'default';

$instance = PipingBag::create($modules, $cache);
$filter = new ControllerFactoryFilter($instance);
DispatcherFactory::add($filter);
