<?php

namespace Xemoe\Container\Traits;

use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;

trait ServicesTrait
{
    protected $accept;
    protected $services;

    public function attachable(array $classes)
    {
        if (empty($classes)) {
            throw new InvalidArgumentException;
        }

        //
        // Only static method
        // use $i = static::getInstance()
        // instead of $this
        //
        $this->accept = $classes;
    }

    public static function attach($object, $alias = null)
    {
        $attachable = false;
        $i = static::getInstance();
        foreach ($i->accept as $contracts) {
            if (in_array($contracts, class_implements($object))) {

                if (!is_null($alias)) {
                    $i->services[$alias] = $object;
                } else {
                    $i->services[get_class($object)] = $object;
                }

                $attachable = true;
            }
        }

        if (!$attachable) {
            throw new InvalidArgumentException;
        }
    }

    public static function detach($classname)
    {
        $i = static::getInstance();
        if (isset($i->services[$classname])) {
            unset($i->services[$classname]);
        }
    }

    public static function resolve($classname)
    {
        $i = static::getInstance();
        if (!isset($i->services[$classname])) {
            throw new UnresolvableException($classname);
        }

        return $i->services[$classname];
    }

    public static function has($classname)
    {
        $i = static::getInstance();
        return isset($i->services[$classname]);
    }

    public static function clean()
    {
        $i = static::getInstance();
        $i->services = [];
    }
}
