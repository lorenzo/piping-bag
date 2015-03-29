<?php

namespace PipingBag\Module;

use Ray\Di\AbstractModule;

class HttpModule extends AbstractModule
{

    public function configure()
    {
        $this->bind('Cake\Network\Request')
        ->annotatedWith('current')
        ->toProvider('PipingBag\Provider\RequestProvider');

        $this->bind('Cake\Network\Response')
        ->annotatedWith('current')
        ->toProvider('PipingBag\Provider\ResponseProvider');

        $this->bind('Cake\Controller\Controller')
        ->annotatedWith('current')
        ->toProvider('PipingBag\Provider\ControllerProvider');

        $this->bind('PipingBag\Routing\Filter\ControllerFactoryFilter');
    }
}
