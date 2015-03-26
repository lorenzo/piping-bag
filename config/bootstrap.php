<?php

use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\Routing\DispatcherFactory;
use PipingBag\Di\PipingBag;
use Doctrine\Common\Annotations\AnnotationReader;

$config = Configure::consume('PipingBag');
$modules = !empty($config['modules']) ? $config['modules'] : [];
$cache = isset($config['cacheConfig']) ? $config['cacheConfig'] : 'default';

AnnotationReader::addGlobalIgnoredName('triggers');
$instance = Cache::remember('pipingbag.instance', function () use ($modules) {
    return PipingBag::create($modules);
}, $cache);

DispatcherFactory::add(
    $instance->getInstance('PipingBag\Routing\Filter\ControllerFactoryFilter')
);
