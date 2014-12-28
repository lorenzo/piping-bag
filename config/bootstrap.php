<?php

use Cake\Routing\DispatcherFactory;
use PipingBag\Di\PipingBag;
use PipingBag\Routing\Filter\ControllerFactoryFilter;

$filter = new ControllerFactoryFilter(PipingBag::create());
DispatcherFactory::add($filter);
