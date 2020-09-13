<?php
declare(strict_types=1);

namespace PipingBag\Interceptor;

use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use PipingBag\Di\PipingBag;

class AssistedInterceptor implements MethodInterceptor
{
    /**
     * Intercepts any method and injects instances of the missing arguments
     * when they are type hinted
     */
    public function invoke(MethodInvocation $invocation)
    {
        $object = $invocation->getThis();
        $parameters = $invocation->getMethod()->getParameters();
        $arguments = $invocation->getArguments()->getArrayCopy();
        $assisted = [];

        foreach ($parameters as $k => $p) {
            $hint = $p->getClass();
            if ($hint) {
                $assisted[$k] = PipingBag::get($hint->getName());
                continue;
            }
            if (isset($arguments[$k])) {
                $assisted[$k] = array_shift($arguments);
                continue;
            }
            $assisted[$k] = $p->getDefaultValue();
        }

        $invocation->getArguments()->exchangeArray($assisted);
        return $invocation->proceed();
    }
}
