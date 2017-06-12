<?php

namespace Xemoe;

use \Xemoe\Container\Traits\ServicesTrait;
use \Xemoe\Container\Traits\SingletonTrait;
use \Xemoe\Contracts\ResolvableContract;
use \Xemoe\Contracts\ServicesContract;

class ServicesContainer implements ServicesContract,ResolvableContract
{
    use SingletonTrait,
        ServicesTrait;

    protected function __construct()
    {
        static::attachable([
            \Xemoe\Contracts\WrapperContract::class,
            \Xemoe\Contracts\ShellObserverContract::class,
        ]);
    }
}
