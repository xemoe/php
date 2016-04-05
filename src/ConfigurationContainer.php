<?php

namespace Xemoe;

use \Xemoe\Contracts\ConfigurationContract;
use \Xemoe\Contracts\EnvLoaderContract;
use \Xemoe\Container\Traits\ConfigurationTrait;
use \Xemoe\Container\Traits\EnvLoaderTrait;
use \Xemoe\Container\Traits\SingletonTrait;

class ConfigurationContainer implements ConfigurationContract, EnvLoaderContract
{
    use ConfigurationTrait,
        EnvLoaderTrait,
        SingletonTrait;
}
