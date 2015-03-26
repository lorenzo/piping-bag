<?php

namespace PipingBag\Provider;

use Cake\Network\Response;
use Ray\Di\Di\Inject;
use Ray\Di\ProviderInterface;

class ResponseProvider implements ProviderInterface
{

    protected static $response;

    public function get() {
        return static::$response;
    }

    public static function set(Response $r) {
        return static::$response = $r;
    }

}
