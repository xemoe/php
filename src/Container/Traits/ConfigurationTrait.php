<?php

namespace Xemoe\Container\Traits;

trait ConfigurationTrait
{
    public function attachable(array $classes)
    {
        $this->accept = $classes;
    }

    public static function set($name, $value)
    {
        $i = static::getInstance();
        if (in_array($name, $i->accept)) {
            $i->properties[$name] = $value;
        }
    }

    public static function get($name)
    {
        if (static::has($name)) {
            $i = static::getInstance();
            return $i->properties[$name];
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