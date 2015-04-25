<?php

use Cake\Core\Configure;
use Cake\Cache\Cache;
use PipingBag\Di\PipingBag;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;

$config = Configure::consume('PipingBag');
$modules = !empty($config['modules']) ? $config['modules'] : [];
$cache = isset($config['cacheConfig']) ? $config['cacheConfig'] : 'default';

AnnotationReader::addGlobalIgnoredName('triggers');
AnnotationRegistry::registerFile(dirname(__DIR__) . '/src/Annotation/Assisted.php');
$instance = Cache::read('pipingbag.instance');

if (!$instance) {
    $instance = PipingBag::create($modules);
}
PipingBag::container($instance);

register_shutdown_function(function () use ($instance, $cache) {
    Cache::write('pipingbag.instance', $instance, $cache);
});
