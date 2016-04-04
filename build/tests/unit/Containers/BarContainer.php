<?php

namespace Unit\Containers;

use \Xemoe\Container\Traits\ServicesTrait;
use \Xemoe\Container\Traits\SingletonTrait;
use \Xemoe\Contracts\ResolvableContract;
use \Xemoe\Contracts\ServicesContract;

class BarContainer implements ServicesContract,ResolvableContract
{
    use SingletonTrait,
        ServicesTrait;

    protected function __construct()
    {
        static::attachable([
            \Xemoe\Contracts\WrapperContract::class,
        ]);
    }
}
