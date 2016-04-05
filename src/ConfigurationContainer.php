<?php

namespace Xemoe;

use \Xemoe\Contracts\ConfigurationContract;
use \Xemoe\Container\Traits\ConfigurationTrait;
use \Xemoe\Container\Traits\SingletonTrait;

class ConfigurationContainer implements ConfigurationContract
{
    use ConfigurationTrait,
        SingletonTrait;
}
