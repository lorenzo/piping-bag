<?php

namespace PipingBag\Provider;

use Cake\Network\Request;
use Ray\Di\Di\Inject;
use Ray\Di\ProviderInterface;

class RequestProvider implements ProviderInterface
{

    protected static $request;

    public function get()
    {
        return static::$request;
    }

    public static function set(Request $r)
    {
        return static::$request = $r;
    }
}
