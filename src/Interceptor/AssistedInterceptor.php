<?php
namespace PipingBag\Interceptor;

use PipingBag\Di\PipingBag;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

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
