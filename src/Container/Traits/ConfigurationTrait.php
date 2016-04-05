<?php

namespace Xemoe\Container\Traits;

use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;

trait ConfigurationTrait
{
    protected $accept;
    protected $properties;

    public function attachable(array $keys)
    {
        if (empty($keys)) {
            throw new InvalidArgumentException;
        }

        //
        // Only static method
        // use $i = static::getInstance()
        // instead of $this
        //
        $this->accept = $keys;
    }

    public static function set($name, $value)
    {
        $i = static::getInstance();
        if (in_array($name, $i->accept)) {
            $i->properties[$name] = $value;
        } else {
            throw new InvalidArgumentException;
        }
    }

    public static function get($name)
    {
        if (static::has($name)) {
            $i = static::getInstance();
            return $i->properties[$name];
        } else {
            throw new UnresolvableException;
        }

        return false;
    }

    public static function has($name)
    {
        $i = static::getInstance();
        return isset($i->properties[$name]);
    }

    public static function clean()
    {
        $i = static::getInstance();
        $i->properties = [];
    }
}
