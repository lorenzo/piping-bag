<?php

use Cake\Core\Configure;
use Cake\Cache\Cache;
use PipingBag\Di\PipingBag;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Cake\Filesystem\Folder;
use Cake\Core\Exception\Exception;

/**
 * Path to the temporary files directory.
 */
if (!defined('PIPIN_BAG_TMP')) {
    define('PIPIN_BAG_TMP', PLUGINS. 'PipingBag' .DS .'tmp' . DS);
}
/**
 * Path to the logs directory.
 */
if (!defined('PIPIN_BAG_LOGS')) {
    define('PIPIN_BAG_LOGS', PLUGINS. 'PipingBag' .DS .'logs' . DS);
}

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
if (!defined('PIPIN_BAG_CACHE')) {
    define('PIPIN_BAG_CACHE', PIPIN_BAG_TMP . 'cache' . DS);
}

if (!is_dir(PIPIN_BAG_CACHE) && !(new Folder(PIPIN_BAG_CACHE, true, 0755)) && !is_dir(PIPIN_BAG_CACHE)) {
    throw new Exception(__d('PipingBag', sprintf('Could not create directory %s', PIPIN_BAG_CACHE)));
}

/*
 * Read configuration file and inject configuration
 */
try {
    Configure::load('PipingBag.config', 'default', false);
} catch (Exception $e) {
    // nothing
}

$config = Configure::consume('PipingBag');
$modules = !empty($config['modules']) ? $config['modules'] : [];
$cache = 'default';
if (!empty($config['Cache'])) {
    Cache::setConfig($config['Cache']['name'], $config['Cache']['config']);
    $cache = $config['Cache']['name'];
}

AnnotationReader::addGlobalIgnoredName('triggers');
AnnotationRegistry::registerFile(dirname(__DIR__) . '/src/Annotation/Assisted.php');
$instance = Cache::read('pipingbag.instance', $cache);
if (!$instance) {
    $instance = PipingBag::create($modules);
}
PipingBag::container($instance);

register_shutdown_function(function () use ($instance, $cache) {
    Cache::write('pipingbag.instance', $instance, $cache);
});
