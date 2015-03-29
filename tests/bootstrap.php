<?php

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;

require_once '../../../vendor/cakephp/cakephp/src/basics.php';
//define('ROOT', $root . DS . 'tests' . DS . 'test_app' . DS);
//define('APP', ROOT . 'App' . DS);
//define('TMP', sys_get_temp_dir() . DS);

Plugin::load('PipingBag', ['path' => dirname(__DIR__)]);
