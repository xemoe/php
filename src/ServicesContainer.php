<?php

namespace Xemoe;

use \Xemoe\Container\Traits\ServicesTrait;
use \Xemoe\Contracts\ResolvableContract;
use \Xemoe\Contracts\ServicesContract;

class ServicesContainer implements ServicesContract,ResolvableContract
{
    use ServicesTrait;

    //
    // @singleton
    //
    
    protected static $instance;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    private function __clone() {}
    private function __wakeup() {}

    //
    // End
    //

    protected $accept;
    protected $services;

    protected function __construct()
    {
        static::attachable([
            \Xemoe\Contracts\WrapperContract::class,
        ]);
    }
}
