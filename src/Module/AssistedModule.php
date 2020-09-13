<?php
declare(strict_types=1);

namespace PipingBag\Module;

use Ray\Di\AbstractModule;

class AssistedModule extends AbstractModule
{

    /**
     * @return void
     */
    protected function configure() : void
    {
        // @Assisted
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith('PipingBag\Annotation\Assisted'),
            ['PipingBag\Interceptor\AssistedInterceptor']
        );
    }
}
