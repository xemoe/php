<?php

namespace Xemoe\Container\Traits;

use \josegonzalez\Dotenv\Loader;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;

trait EnvLoaderTrait
{
    protected static $env;

    public static function loadEnv($filepath, array $options = [])
    {
        $loader = (new Loader($filepath))->raiseExceptions(true)->parse();

        if (!empty($options)) {
            extract($options);
        }

        if (isset($expect) && is_array($expect)) {
            $loader = $loader->expect($expect);
        }

        static::$env = $loader->toArray();
    }

    public static function getEnv($name)
    {
        return static::$env[$name];
    }
}
