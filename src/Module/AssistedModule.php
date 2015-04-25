<?php

namespace PipingBag\Module;

use Ray\Di\AbstractModule;

class AssistedModule extends AbstractModule
{

    protected function configure()
    {
        // @Assisted
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith('PipingBag\Annotation\Assisted'),
            ['PipingBag\Interceptor\AssistedInterceptor']
        );
    }
}
